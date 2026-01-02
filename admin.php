<?php
require_once 'config.php';

// --- 1. Security Check ---
if (!isLoggedIn()) {
    redirectToLogin();
}

// --- 2. Action Handlers (Delete, Status, etc.) ---
$action_req = isset($_GET['action']) ? sanitize($_GET['action']) : '';
$type_req = isset($_GET['type']) ? sanitize($_GET['type']) : '';
$id_req = isset($_GET['id']) ? intval($_GET['id']) : 0;
$section_req = isset($_GET['section']) ? sanitize($_GET['section']) : 'dashboard';

// Handle Delete
if ($action_req === 'delete' && $id_req > 0) {
    $sql = "";
    switch ($type_req) {
        case 'course':
            $sql = "DELETE FROM courses WHERE id = $id_req";
            break;
        case 'subject':
            $sql = "DELETE FROM subjects WHERE id = $id_req";
            break;
        case 'book':
            $sql = "DELETE FROM books WHERE id = $id_req";
            break;
        case 'speaking_example':
            $sql = "DELETE FROM speaking_examples WHERE id = $id_req";
            break;
        case 'video_resource':
            $sql = "DELETE FROM video_resources WHERE id = $id_req";
            break;
    }

    if ($sql && mysqli_query($conn, $sql)) {
        header("Location: admin.php?section=$section_req&message=Deleted successfully&type=success");
    } else {
        header("Location: admin.php?section=$section_req&message=Error deleting item&type=error");
    }
    exit();
}

// Handle Status Toggle
if ($action_req === 'toggle_status' && $id_req > 0) {
    $new_status = isset($_GET['status']) ? sanitize($_GET['status']) : 'active';
    $sql = "";

    switch ($type_req) {
        case 'course':
            $sql = "UPDATE courses SET status = '$new_status' WHERE id = $id_req";
            break;
        case 'subject':
            $sql = "UPDATE subjects SET status = '$new_status' WHERE id = $id_req";
            break;
        case 'book':
            $sql = "UPDATE books SET status = '$new_status' WHERE id = $id_req";
            break;
        case 'speaking_example':
            $sql = "UPDATE speaking_examples SET status = '$new_status' WHERE id = $id_req";
            break;
        case 'video_resource':
            $sql = "UPDATE video_resources SET status = '$new_status' WHERE id = $id_req";
            break;
    }

    if ($sql && mysqli_query($conn, $sql)) {
        header("Location: admin.php?section=$section_req");
    } else {
        header("Location: admin.php?section=$section_req&message=Error updating status&type=error");
    }
    exit();
}

// --- 3. Handle POST Form Submissions ---
$message = isset($_GET['message']) ? sanitize($_GET['message']) : '';
$message_type = isset($_GET['type']) ? sanitize($_GET['type']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $post_action = sanitize($_POST['action']);

    switch ($post_action) {
        case 'update_settings':
            // In the update_settings case, add to the $fields array:
            $fields = [
                'site_name',
                'tagline',
                'primary_color',
                'secondary_color',
                'accent_color',
                'primary_phone',
                'secondary_phone',
                'whatsapp_number',
                'email',
                'address',
                'google_map_embed',
                'android_app_link',       // ADD THIS
                'ios_app_link',           // ADD THIS
                'ios_org_pass'            // ADD THIS (not displayed in header)
            ];

            // No additional changes needed in the update logic as the existing loop handles all fields
            $updates = [];
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $value = sanitize($_POST[$field]);
                    $updates[] = "$field = '$value'";
                }
            }

            // Handle logo upload
            if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/logos/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $logo_name = time() . '_' . basename($_FILES['logo_file']['name']);
                $logo_path = $upload_dir . $logo_name;

                // For logo upload
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $logo_path)) {

                    $updates[] = "logo_url = '" . $logo_path . "'"; // ADD THIS LINE
                }
            } elseif (isset($_POST['logo_url_current'])) {
                $updates[] = "logo_url = '" . sanitize($_POST['logo_url_current']) . "'";
            }

            // Handle favicon upload
            if (isset($_FILES['favicon_file']) && $_FILES['favicon_file']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/favicons/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $favicon_name = time() . '_' . basename($_FILES['favicon_file']['name']);
                $favicon_path = $upload_dir . $favicon_name;

                // For favicon upload
                if (move_uploaded_file($_FILES['favicon_file']['tmp_name'], $favicon_path)) {

                    $updates[] = "favicon_url = '" . $favicon_path . "'"; // ADD THIS LINE
                }
            } elseif (isset($_POST['favicon_url_current'])) {
                $updates[] = "favicon_url = '" . sanitize($_POST['favicon_url_current']) . "'";
            }

            // Social media URLs (not file uploads)
            $social_media = ['facebook', 'instagram', 'twitter', 'linkedin', 'youtube'];
            foreach ($social_media as $platform) {
                $url_key = $platform . '_url';
                if (isset($_POST[$url_key])) {
                    $value = sanitize($_POST[$url_key]);
                    $updates[] = "$url_key = '$value'";
                }
            }

            // Social media and feature toggles
            $show_facebook = isset($_POST['show_facebook']) ? 1 : 0;
            $show_instagram = isset($_POST['show_instagram']) ? 1 : 0;
            $show_twitter = isset($_POST['show_twitter']) ? 1 : 0;
            $show_linkedin = isset($_POST['show_linkedin']) ? 1 : 0;
            $show_youtube = isset($_POST['show_youtube']) ? 1 : 0;
            $enable_whatsapp = isset($_POST['enable_whatsapp_chat']) ? 1 : 0;
            $enable_newsletter = isset($_POST['enable_newsletter']) ? 1 : 0;

            $updates[] = "show_facebook = $show_facebook";
            $updates[] = "show_instagram = $show_instagram";
            $updates[] = "show_twitter = $show_twitter";
            $updates[] = "show_linkedin = $show_linkedin";
            $updates[] = "show_youtube = $show_youtube";
            $updates[] = "enable_whatsapp_chat = $enable_whatsapp";
            $updates[] = "enable_newsletter = $enable_newsletter";
            $updates[] = "updated_at = NOW()";

            $sql = "UPDATE settings SET " . implode(', ', $updates) . " WHERE id = 1";

            if (mysqli_query($conn, $sql)) {
                $message = 'Settings updated successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error updating settings: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;

        case 'add_course':
            $name = sanitize($_POST['name']);
            $slug = generateSlug($name);
            $short_desc = sanitize($_POST['short_description']);
            $full_desc = sanitize($_POST['full_description']);
            $icon = sanitize($_POST['icon']);
            $status = sanitize($_POST['status']);
            $featured = isset($_POST['is_featured']) ? 1 : 0;
            $popular = isset($_POST['is_popular']) ? 1 : 0;
            $order = intval($_POST['sort_order']);

            // Handle cover image upload
            $cover_image = '';
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/courses/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $image_name = time() . '_' . basename($_FILES['cover_image']['name']);
                $image_path = $upload_dir . $image_name;

                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $image_path)) {

                    $cover_image = $image_path; // ADD THIS LINE
                }
            }

            $sql = "INSERT INTO courses (name, slug, short_description, full_description, icon, cover_image, 
                    status, is_featured, is_popular, sort_order) 
                    VALUES ('$name', '$slug', '$short_desc', '$full_desc', '$icon', '$cover_image',
                    '$status', $featured, $popular, $order)";

            if (mysqli_query($conn, $sql)) {
                $message = 'Course added successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error adding course: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;

        case 'edit_course':
            $id = intval($_POST['id']);
            $name = sanitize($_POST['name']);
            $short_desc = sanitize($_POST['short_description']);
            $full_desc = sanitize($_POST['full_description']);
            $icon = sanitize($_POST['icon']);
            $status = sanitize($_POST['status']);
            $featured = isset($_POST['is_featured']) ? 1 : 0;
            $popular = isset($_POST['is_popular']) ? 1 : 0;
            $order = intval($_POST['sort_order']);

            // Handle cover image upload
            $cover_image_sql = '';
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/courses/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $image_name = time() . '_' . basename($_FILES['cover_image']['name']);
                $image_path = $upload_dir . $image_name;

                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $image_path)) {

                    $cover_image = $image_path; // ADD THIS LINE
                    $cover_image_sql = ", cover_image = '$cover_image'";
                }
            } elseif (isset($_POST['cover_image_current']) && !empty($_POST['cover_image_current'])) {
                $cover_image_sql = ", cover_image = '" . sanitize($_POST['cover_image_current']) . "'";
            }

            $sql = "UPDATE courses SET 
                    name = '$name',
                    short_description = '$short_desc',
                    full_description = '$full_desc',
                    icon = '$icon'
                    $cover_image_sql,
                    status = '$status',
                    is_featured = $featured,
                    is_popular = $popular,
                    sort_order = $order,
                    updated_at = NOW()
                    WHERE id = $id";

            if (mysqli_query($conn, $sql)) {
                header('Location: admin.php?section=courses&message=Course updated successfully&type=success');
                exit();
            } else {
                $message = 'Error updating course: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;

        case 'add_subject':
            $course_id = intval($_POST['course_id']);
            $subject_name = sanitize($_POST['subject_name']);
            $slug = generateSlug($subject_name);
            $duration = sanitize($_POST['duration']);
            $duration_type = sanitize($_POST['duration_type']);
            $price = floatval($_POST['price']);
            $discount_price = !empty($_POST['discount_price']) ? floatval($_POST['discount_price']) : null;
            $description = sanitize($_POST['description']);
            $highlights = sanitize($_POST['highlights']);
            $what_you_learn = sanitize($_POST['what_you_learn']);
            $prerequisites = sanitize($_POST['prerequisites']);
            $bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
            $recommended = isset($_POST['is_recommended']) ? 1 : 0;
            $order = intval($_POST['display_order']);
            $status = sanitize($_POST['status']);

            $features = [];
            if (isset($_POST['features'])) {
                $features_array = explode("\n", $_POST['features']);
                $features = array_map('trim', $features_array);
                $features = array_filter($features);
                $features_json = json_encode($features);
            } else {
                $features_json = '[]';
            }

            $sql = "INSERT INTO subjects (course_id, subject_name, slug, duration, duration_type, 
                    price, discount_price, description, highlights, what_you_learn, 
                    prerequisites, features, is_bestseller, is_recommended, display_order, status) 
                    VALUES ($course_id, '$subject_name', '$slug', '$duration', '$duration_type', 
                    $price, " . ($discount_price ? "$discount_price" : "NULL") . ", 
                    '$description', '$highlights', '$what_you_learn', '$prerequisites', 
                    '$features_json', $bestseller, $recommended, $order, '$status')";

            if (mysqli_query($conn, $sql)) {
                $message = 'Subject/Module added successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error adding subject: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;

        case 'edit_subject':
            $id = intval($_POST['id']);
            $course_id = intval($_POST['course_id']);
            $subject_name = sanitize($_POST['subject_name']);
            $duration = sanitize($_POST['duration']);
            $duration_type = sanitize($_POST['duration_type']);
            $price = floatval($_POST['price']);
            $discount_price = !empty($_POST['discount_price']) ? floatval($_POST['discount_price']) : null;
            $description = sanitize($_POST['description']);
            $highlights = sanitize($_POST['highlights']);
            $what_you_learn = sanitize($_POST['what_you_learn']);
            $prerequisites = sanitize($_POST['prerequisites']);
            $bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
            $recommended = isset($_POST['is_recommended']) ? 1 : 0;
            $order = intval($_POST['display_order']);
            $status = sanitize($_POST['status']);

            $features = [];
            if (isset($_POST['features'])) {
                $features_array = explode("\n", $_POST['features']);
                $features = array_map('trim', $features_array);
                $features = array_filter($features);
                $features_json = json_encode($features);
            } else {
                $features_json = '[]';
            }

            $sql = "UPDATE subjects SET 
                    course_id = $course_id,
                    subject_name = '$subject_name',
                    duration = '$duration',
                    duration_type = '$duration_type',
                    price = $price,
                    discount_price = " . ($discount_price ? "$discount_price" : "NULL") . ",
                    description = '$description',
                    highlights = '$highlights',
                    what_you_learn = '$what_you_learn',
                    prerequisites = '$prerequisites',
                    features = '$features_json',
                    is_bestseller = $bestseller,
                    is_recommended = $recommended,
                    display_order = $order,
                    status = '$status',
                    updated_at = NOW()
                    WHERE id = $id";

            if (mysqli_query($conn, $sql)) {
                header('Location: admin.php?section=subjects&message=Subject updated successfully&type=success');
                exit();
            } else {
                $message = 'Error updating subject: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;

        case 'add_book':
            $subject_id = isset($_POST['subject_id']) && !empty($_POST['subject_id']) ? intval($_POST['subject_id']) : NULL;
            $title = sanitize($_POST['title']);
            $slug = generateSlug($title);
            $author = sanitize($_POST['author']);
            $publisher = sanitize($_POST['publisher']);
            $edition = sanitize($_POST['edition']);
            $isbn = sanitize($_POST['isbn']);
            $description = sanitize($_POST['description']);
            $pages = intval($_POST['pages']);
            $language = sanitize($_POST['language']);
            $price = floatval($_POST['price']);
            $is_free = isset($_POST['is_free']) ? 1 : 0;
            
            // If the item is marked as free, force the price to 0
            if ($is_free) {
                $price = 0.00;
            }
            $is_independent = isset($_POST['is_independent']) ? 1 : 0;
            $file_format = isset($_POST['file_format']) ? sanitize($_POST['file_format']) : '';
            $featured = isset($_POST['is_featured']) ? 1 : 0;
            $recommended = isset($_POST['is_recommended']) ? 1 : 0;
            $status = sanitize($_POST['status']);

            // Handle file upload
            $file_url = '';
            $file_size = '';
            if (isset($_FILES['book_file']) && $_FILES['book_file']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/books/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $file_name = time() . '_' . basename($_FILES['book_file']['name']);
                $file_path = $upload_dir . $file_name;

                // For book file
                if (move_uploaded_file($_FILES['book_file']['tmp_name'], $file_path)) {

                    $file_url = $file_path; // ADD THIS LINE
                    $file_size = formatBytes($_FILES['book_file']['size']);
                }
            }

            // Handle cover image upload
            $cover_image = '';
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/book_covers/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $image_name = time() . '_' . basename($_FILES['cover_image']['name']);
                $image_path = $upload_dir . $image_name;

                // For cover image
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $image_path)) {

                    $cover_image = $image_path; // ADD THIS LINE
                }
            }

            // If independent, subject_id should be NULL
            if ($is_independent) {
                $subject_id = NULL;
            }

            $sql = "INSERT INTO books (subject_id, title, slug, author, publisher, edition, 
                    isbn, description, cover_image, pages, language, price, is_free, is_independent, 
                    file_url, file_size, file_format, is_featured, 
                    is_recommended, status) 
                    VALUES (" . ($subject_id ? $subject_id : "NULL") . ", '$title', '$slug', '$author', '$publisher', 
                    '$edition', '$isbn', '$description', '$cover_image', $pages, '$language', $price, 
                    $is_free, $is_independent, '$file_url', '$file_size', '$file_format', $featured, $recommended, '$status')";

            if (mysqli_query($conn, $sql)) {
                $message = 'Book/Study Material added successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error adding book: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;

        case 'edit_book':
            $id = intval($_POST['id']);
            $subject_id = isset($_POST['subject_id']) && !empty($_POST['subject_id']) ? intval($_POST['subject_id']) : NULL;
            $title = sanitize($_POST['title']);
            $author = sanitize($_POST['author']);
            $publisher = sanitize($_POST['publisher']);
            $edition = sanitize($_POST['edition']);
            $isbn = sanitize($_POST['isbn']);
            $description = sanitize($_POST['description']);
            $pages = intval($_POST['pages']);
            $language = sanitize($_POST['language']);
            $price = floatval($_POST['price']);
            $is_free = isset($_POST['is_free']) ? 1 : 0;
            
            // If the item is marked as free, force the price to 0
            if ($is_free) {
                $price = 0.00;
            }
            $is_independent = isset($_POST['is_independent']) ? 1 : 0;
            $file_format = isset($_POST['file_format']) ? sanitize($_POST['file_format']) : '';
            $featured = isset($_POST['is_featured']) ? 1 : 0;
            $recommended = isset($_POST['is_recommended']) ? 1 : 0;
            $status = sanitize($_POST['status']);

            // Handle file upload
            $file_url_sql = '';
            $file_size_sql = '';
            if (isset($_FILES['book_file']) && $_FILES['book_file']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/books/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $file_name = time() . '_' . basename($_FILES['book_file']['name']);
                $file_path = $upload_dir . $file_name;

                // For book file
                if (move_uploaded_file($_FILES['book_file']['tmp_name'], $file_path)) {

                    $file_url = $file_path; // ADD THIS LINE
                    $file_size = formatBytes($_FILES['book_file']['size']);
                    $file_url_sql = ", file_url = '$file_url', file_size = '$file_size'";
                }
            } elseif (isset($_POST['file_url_current']) && !empty($_POST['file_url_current'])) {
                $file_url_sql = ", file_url = '" . sanitize($_POST['file_url_current']) . "', file_size = '" . sanitize($_POST['file_size_current']) . "'";
            }

            // Handle cover image upload
            $cover_image_sql = '';
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/book_covers/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $image_name = time() . '_' . basename($_FILES['cover_image']['name']);
                $image_path = $upload_dir . $image_name;

                // For cover image
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $image_path)) {

                    $cover_image = $image_path; // ADD THIS LINE
                    $cover_image_sql = ", cover_image = '$cover_image'";
                }
            } elseif (isset($_POST['cover_image_current']) && !empty($_POST['cover_image_current'])) {
                $cover_image_sql = ", cover_image = '" . sanitize($_POST['cover_image_current']) . "'";
            }

            // If independent, subject_id should be NULL
            if ($is_independent) {
                $subject_id = NULL;
            }

            $sql = "UPDATE books SET 
                    subject_id = " . ($subject_id ? $subject_id : "NULL") . ",
                    title = '$title',
                    author = '$author',
                    publisher = '$publisher',
                    edition = '$edition',
                    isbn = '$isbn',
                    description = '$description',
                    pages = $pages,
                    language = '$language',
                    price = $price,
                    is_free = $is_free,
                    is_independent = $is_independent,
                    file_format = '$file_format'
                    $file_url_sql
                    $cover_image_sql,
                    is_featured = $featured,
                    is_recommended = $recommended,
                    status = '$status',
                    updated_at = NOW()
                    WHERE id = $id";

            if (mysqli_query($conn, $sql)) {
                header('Location: admin.php?section=books&message=Book updated successfully&type=success');
                exit();
            } else {
                $message = 'Error updating book: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;

        case 'add_speaking_example':
            $title = sanitize($_POST['title']);
            $description = sanitize($_POST['description']);
            $duration = sanitize($_POST['duration']);
            $transcript = sanitize($_POST['transcript']);
            $level = sanitize($_POST['level']);
            $tags = sanitize($_POST['tags']);
            $featured = isset($_POST['is_featured']) ? 1 : 0;
            $status = sanitize($_POST['status']);

            // Handle audio file upload
            $audio_file = '';
            $file_size = '';
            if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/audio/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $audio_name = time() . '_' . basename($_FILES['audio_file']['name']);
                $audio_path = $upload_dir . $audio_name;

                if (move_uploaded_file($_FILES['audio_file']['tmp_name'], $audio_path)) {

                    $audio_file = $audio_path; // ADD THIS LINE
                    $file_size = formatBytes($_FILES['audio_file']['size']);
                }
            }

            $sql = "INSERT INTO speaking_examples (title, description, audio_file, file_size, 
                    duration, transcript, level, tags, is_featured, status) 
                    VALUES ('$title', '$description', '$audio_file', '$file_size', 
                    '$duration', '$transcript', '$level', '$tags', $featured, '$status')";

            if (mysqli_query($conn, $sql)) {
                $message = 'Speaking Example added successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error adding speaking example: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;

        case 'edit_speaking_example':
            $id = intval($_POST['id']);
            $title = sanitize($_POST['title']);
            $description = sanitize($_POST['description']);
            $duration = sanitize($_POST['duration']);
            $transcript = sanitize($_POST['transcript']);
            $level = sanitize($_POST['level']);
            $tags = sanitize($_POST['tags']);
            $featured = isset($_POST['is_featured']) ? 1 : 0;
            $status = sanitize($_POST['status']);

            // Handle audio file upload
            $audio_file_sql = '';
            $file_size_sql = '';
            if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/audio/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $audio_name = time() . '_' . basename($_FILES['audio_file']['name']);
                $audio_path = $upload_dir . $audio_name;

                if (move_uploaded_file($_FILES['audio_file']['tmp_name'], $audio_path)) {

                    $audio_file = $audio_path; // ADD THIS LINE
                    $file_size = formatBytes($_FILES['audio_file']['size']);
                    $audio_file_sql = ", audio_file = '$audio_file', file_size = '$file_size'";
                }
            } elseif (isset($_POST['audio_file_current']) && !empty($_POST['audio_file_current'])) {
                $audio_file_sql = ", audio_file = '" . sanitize($_POST['audio_file_current']) . "', file_size = '" . sanitize($_POST['file_size_current']) . "'";
            }

            $sql = "UPDATE speaking_examples SET 
                    title = '$title',
                    description = '$description'
                    $audio_file_sql,
                    duration = '$duration',
                    transcript = '$transcript',
                    level = '$level',
                    tags = '$tags',
                    is_featured = $featured,
                    status = '$status',
                    updated_at = NOW()
                    WHERE id = $id";

            if (mysqli_query($conn, $sql)) {
                header('Location: admin.php?section=speaking_examples&message=Speaking Example updated successfully&type=success');
                exit();
            } else {
                $message = 'Error updating speaking example: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;

        case 'add_video_resource':
            $title = sanitize($_POST['title']);
            $description = sanitize($_POST['description']);
            $video_url = sanitize($_POST['video_url']);
            $platform = sanitize($_POST['platform']);
            $duration = sanitize($_POST['duration']);
            $category = sanitize($_POST['category']);
            $tags = sanitize($_POST['tags']);
            $featured = isset($_POST['is_featured']) ? 1 : 0;
            $status = sanitize($_POST['status']);

            // Handle thumbnail upload
            $thumbnail_url = '';
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/thumbnails/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $thumb_name = time() . '_' . basename($_FILES['thumbnail']['name']);
                $thumb_path = $upload_dir . $thumb_name;

                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumb_path)) {

                    $thumbnail_url = $thumb_path; // ADD THIS LINE
                }
            }

            $sql = "INSERT INTO video_resources (title, description, video_url, thumbnail_url, 
                    platform, duration, category, tags, is_featured, status) 
                    VALUES ('$title', '$description', '$video_url', '$thumbnail_url', 
                    '$platform', '$duration', '$category', '$tags', $featured, '$status')";

            if (mysqli_query($conn, $sql)) {
                $message = 'Video Resource added successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error adding video resource: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;

        case 'edit_video_resource':
            $id = intval($_POST['id']);
            $title = sanitize($_POST['title']);
            $description = sanitize($_POST['description']);
            $video_url = sanitize($_POST['video_url']);
            $platform = sanitize($_POST['platform']);
            $duration = sanitize($_POST['duration']);
            $category = sanitize($_POST['category']);
            $tags = sanitize($_POST['tags']);
            $featured = isset($_POST['is_featured']) ? 1 : 0;
            $status = sanitize($_POST['status']);

            // Handle thumbnail upload
            $thumbnail_url_sql = '';
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/thumbnails/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $thumb_name = time() . '_' . basename($_FILES['thumbnail']['name']);
                $thumb_path = $upload_dir . $thumb_name;

                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumb_path)) {

                    $thumbnail_url = $thumb_path; // ADD THIS LINE
                    $thumbnail_url_sql = ", thumbnail_url = '$thumbnail_url'";
                }
            } elseif (isset($_POST['thumbnail_url_current']) && !empty($_POST['thumbnail_url_current'])) {
                $thumbnail_url_sql = ", thumbnail_url = '" . sanitize($_POST['thumbnail_url_current']) . "'";
            }

            $sql = "UPDATE video_resources SET 
                    title = '$title',
                    description = '$description',
                    video_url = '$video_url',
                    platform = '$platform',
                    duration = '$duration',
                    category = '$category',
                    tags = '$tags',
                    is_featured = $featured,
                    status = '$status'
                    $thumbnail_url_sql,
                    updated_at = NOW()
                    WHERE id = $id";

            if (mysqli_query($conn, $sql)) {
                header('Location: admin.php?section=video_resources&message=Video Resource updated successfully&type=success');
                exit();
            } else {
                $message = 'Error updating video resource: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;

        case 'add_blog':
            $title = sanitize($_POST['title']);
            $slug = generateSlug($title);
            $content = $_POST['content']; // Don't sanitize HTML content
            $status = sanitize($_POST['status']);

            // Handle image upload
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/blogs/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $image_name = time() . '_' . basename($_FILES['image']['name']);
                $image_path = $upload_dir . $image_name;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    $image = $image_path;
                }
            }

            $sql = "INSERT INTO blogs (title, slug, image, content, status) 
                    VALUES ('$title', '$slug', '$image', '$content', '$status')";

            if (mysqli_query($conn, $sql)) {
                $message = 'Blog post added successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error adding blog post: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;

        case 'edit_blog':
            $id = intval($_POST['id']);
            $title = sanitize($_POST['title']);
            $content = $_POST['content']; // Don't sanitize HTML content
            $status = sanitize($_POST['status']);

            // Handle image upload
            $image_sql = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/blogs/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $image_name = time() . '_' . basename($_FILES['image']['name']);
                $image_path = $upload_dir . $image_name;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    $image = $image_path;
                    $image_sql = ", image = '$image'";
                }
            } elseif (isset($_POST['image_current']) && !empty($_POST['image_current'])) {
                $image_sql = ", image = '" . sanitize($_POST['image_current']) . "'";
            }

            $sql = "UPDATE blogs SET 
                    title = '$title',
                    content = '$content'
                    $image_sql,
                    status = '$status',
                    updated_at = NOW()
                    WHERE id = $id";

            if (mysqli_query($conn, $sql)) {
                header('Location: admin.php?section=blogs&message=Blog post updated successfully&type=success');
                exit();
            } else {
                $message = 'Error updating blog post: ' . mysqli_error($conn);
                $message_type = 'error';
            }
            break;
    }
}

// --- 4. Fetch Data & State ---
$settings = getSettings($conn);
$stats = getDashboardStats($conn);

// Get additional stats for new sections
$speaking_examples_stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total, SUM(CASE WHEN status='published' THEN 1 ELSE 0 END) as published FROM speaking_examples"));
$video_resources_stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total, SUM(CASE WHEN status='published' THEN 1 ELSE 0 END) as published FROM video_resources"));
$blogs_stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total, SUM(CASE WHEN status='published' THEN 1 ELSE 0 END) as published FROM blogs"));

$stats['total_speaking_examples'] = $speaking_examples_stats['published'] ?? 0;
$stats['total_video_resources'] = $video_resources_stats['published'] ?? 0;
$stats['total_blogs'] = $blogs_stats['published'] ?? 0;

// Get current section
$current_section = isset($_GET['section']) ? sanitize($_GET['section']) : 'dashboard';

// Get lists for display
$courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY sort_order, name");
$recent_subjects = mysqli_query($conn, "SELECT s.*, c.name as course_name FROM subjects s 
                                      LEFT JOIN courses c ON s.course_id = c.id 
                                      ORDER BY s.created_at DESC LIMIT 5");
$recent_books = mysqli_query($conn, "SELECT b.*, s.subject_name, c.name as course_name FROM books b
                                   LEFT JOIN subjects s ON b.subject_id = s.id
                                   LEFT JOIN courses c ON s.course_id = c.id
                                   ORDER BY b.created_at DESC LIMIT 5");

// --- 5. Handle Editing State ---
$edit_course = null;
$edit_subject = null;
$edit_book = null;
$edit_speaking_example = null;
$edit_video_resource = null;

if ($action_req === 'edit' && $id_req > 0 && !empty($type_req)) {
    switch ($type_req) {
        case 'course':
            $result = mysqli_query($conn, "SELECT * FROM courses WHERE id = $id_req");
            $edit_course = mysqli_fetch_assoc($result);
            $current_section = 'courses';
            break;
        case 'subject':
            $result = mysqli_query($conn, "SELECT * FROM subjects WHERE id = $id_req");
            $edit_subject = mysqli_fetch_assoc($result);
            $current_section = 'subjects';
            break;
        case 'book':
            $result = mysqli_query($conn, "SELECT * FROM books WHERE id = $id_req");
            $edit_book = mysqli_fetch_assoc($result);
            $current_section = 'books';
            break;
        case 'speaking_example':
            $result = mysqli_query($conn, "SELECT * FROM speaking_examples WHERE id = $id_req");
            $edit_speaking_example = mysqli_fetch_assoc($result);
            $current_section = 'speaking_examples';
            break;
        case 'video_resource':
            $result = mysqli_query($conn, "SELECT * FROM video_resources WHERE id = $id_req");
            $edit_video_resource = mysqli_fetch_assoc($result);
            $current_section = 'video_resources';
            break;
    }
}

// Helper function for file size formatting
function formatBytes($bytes, $decimals = 2)
{
    if ($bytes === 0)
        return '0 Bytes';
    $k = 1024;
    $dm = $decimals < 0 ? 0 : $decimals;
    $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    $i = ($bytes > 0) ? (int) floor(log($bytes) / log($k)) : 0;
    $i = max(0, min($i, count($sizes) - 1));
    $value = $bytes / pow($k, $i);
    return number_format($value, $dm) . ' ' . $sizes[$i];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal Admin | <?php echo htmlspecialchars($settings['site_name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Cinzel:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --royal-dark: #0F0F0F;
            --royal-darker: #080808;
            --royal-black: #000000;
            --royal-gold: #D4AF37;
            --royal-gold-light: #FFD700;
            --royal-gold-dark: #B7950B;
            --royal-accent: #C9A227;
            --royal-light: #1A1A1A;
            --royal-lighter: #222222;
            --royal-gray: #2A2A2A;
            --royal-gray-light: #3A3A3A;
            --royal-text: #E5E5E5;
            --royal-text-light: #F5F5F5;
            --royal-text-dark: #AAAAAA;
            --royal-success: #10B981;
            --royal-warning: #F59E0B;
            --royal-danger: #EF4444;
            --royal-info: #3B82F6;

            --sidebar-width: 280px;
            --header-height: 80px;
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --border-radius-lg: 16px;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 2px 12px rgba(0, 0, 0, 0.3);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 16px 48px rgba(0, 0, 0, 0.5);
            --glow: 0 0 20px rgba(212, 175, 55, 0.2);
            --gold-gradient: linear-gradient(135deg, var(--royal-gold), var(--royal-gold-light));
            --dark-gradient: linear-gradient(135deg, var(--royal-dark), var(--royal-darker));
            --card-gradient: linear-gradient(180deg, var(--royal-light), var(--royal-gray));
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--royal-dark);
            color: var(--royal-text);
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.6;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--royal-light);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--royal-gold);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--royal-gold-light);
        }

        .royal-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--dark-gradient);
            z-index: 1000;
            border-right: 1px solid rgba(212, 175, 55, 0.1);
            box-shadow: var(--shadow);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .sidebar-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gold-gradient);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {

            0%,
            100% {
                background-position: -200px 0;
            }

            50% {
                background-position: calc(100% + 200px) 0;
            }
        }

        .brand-logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            position: relative;
        }

        .logo-inner {
            width: 100%;
            height: 100%;
            background: var(--dark-gradient);
            border-radius: 50%;
            border: 2px solid var(--royal-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .logo-inner i {
            font-size: 28px;
            color: var(--royal-gold);
            position: relative;
            z-index: 1;
        }

        .brand-title {
            font-family: 'Cinzel', serif;
            font-size: 24px;
            font-weight: 700;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .brand-subtitle {
            font-size: 12px;
            color: var(--royal-text-dark);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 25px 15px;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 30px;
        }

        .nav-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--royal-gold);
            margin-bottom: 15px;
            padding: 0 10px;
            opacity: 0.7;
        }

        .nav-item {
            margin-bottom: 5px;
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            color: var(--royal-text-dark);
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover {
            background: rgba(212, 175, 55, 0.05);
            color: var(--royal-text);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(212, 175, 55, 0.1);
            color: var(--royal-gold);
            box-shadow: var(--shadow-sm);
        }

        .nav-icon {
            font-size: 18px;
            width: 24px;
            text-align: center;
            opacity: 0.9;
        }

        .nav-text {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
        }

        .nav-badge {
            background: var(--gold-gradient);
            color: var(--royal-dark);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            min-width: 24px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(212, 175, 55, 0.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: var(--border-radius);
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--gold-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--royal-dark);
            font-weight: 700;
        }

        .user-info h4 {
            font-size: 14px;
            color: var(--royal-text);
            margin-bottom: 2px;
        }

        .user-info span {
            font-size: 12px;
            color: var(--royal-gold);
        }

        .royal-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: var(--transition);
        }

        .royal-header {
            height: var(--header-height);
            background: var(--royal-light);
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .header-left h1 {
            font-family: 'Cinzel', serif;
            font-size: 28px;
            font-weight: 700;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-left p {
            font-size: 14px;
            color: var(--royal-text-dark);
            margin-top: 5px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .action-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--royal-gold);
            cursor: pointer;
            transition: var(--transition);
        }

        .action-btn:hover {
            background: var(--royal-gold);
            color: var(--royal-dark);
            transform: translateY(-2px);
        }

        .time {
            font-size: 18px;
            font-weight: 600;
            color: var(--royal-gold);
            font-family: 'Cinzel', serif;
        }

        .date {
            font-size: 13px;
            color: var(--royal-text-dark);
            text-align: right;
        }

        .content-area {
            padding: 30px;
            max-width: 1600px;
            margin: 0 auto;
        }

        .welcome-banner {
            background: var(--card-gradient);
            border-radius: var(--border-radius-lg);
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .welcome-title {
            font-family: 'Cinzel', serif;
            font-size: 32px;
            font-weight: 700;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .welcome-text {
            color: var(--royal-text);
            font-size: 16px;
            opacity: 0.9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-gradient);
            border-radius: var(--border-radius-lg);
            padding: 25px;
            border: 1px solid rgba(212, 175, 55, 0.1);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--royal-gold);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: rgba(212, 175, 55, 0.1);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--royal-gold);
            font-size: 24px;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: var(--royal-text);
            font-family: 'Cinzel', serif;
        }

        .stat-label {
            font-size: 14px;
            color: var(--royal-text-dark);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .action-card {
            background: var(--card-gradient);
            border-radius: var(--border-radius);
            padding: 25px;
            border: 1px solid rgba(212, 175, 55, 0.1);
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .action-card:hover {
            transform: translateY(-5px);
            border-color: var(--royal-gold);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            background: var(--gold-gradient);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--royal-dark);
            font-size: 22px;
            margin-bottom: 20px;
        }

        .action-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--royal-text);
            margin-bottom: 8px;
        }

        .data-section,
        .form-section {
            background: var(--card-gradient);
            border-radius: var(--border-radius-lg);
            border: 1px solid rgba(212, 175, 55, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .form-section {
            padding: 30px;
        }

        .section-header {
            padding: 25px 30px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title,
        .form-title {
            font-family: 'Cinzel', serif;
            font-size: 20px;
            font-weight: 600;
            color: var(--royal-text);
        }

        .form-title {
            font-size: 24px;
            color: var(--royal-gold);
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: var(--border-radius-sm);
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-gold {
            background: var(--gold-gradient);
            color: var(--royal-dark);
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: var(--glow);
        }

        .btn-dark {
            background: var(--royal-light);
            color: var(--royal-text);
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .btn-dark:hover {
            background: var(--royal-gray);
            border-color: var(--royal-gold);
        }

        .table-container {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: rgba(212, 175, 55, 0.05);
        }

        .data-table th {
            padding: 18px 25px;
            text-align: left;
            font-weight: 600;
            color: var(--royal-gold);
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
            white-space: nowrap;
        }

        .data-table td {
            padding: 18px 25px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.05);
            color: var(--royal-text);
            font-size: 14px;
        }

        .data-table tbody tr:hover {
            background: rgba(212, 175, 55, 0.03);
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--royal-success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: var(--royal-danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--royal-warning);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .action-icon-btn {
            width: 35px;
            height: 35px;
            border-radius: var(--border-radius-sm);
            border: 1px solid rgba(212, 175, 55, 0.2);
            background: rgba(212, 175, 55, 0.05);
            color: var(--royal-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .action-icon-btn:hover {
            background: var(--royal-gold);
            color: var(--royal-dark);
            transform: scale(1.1);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--royal-text);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: var(--border-radius-sm);
            color: var(--royal-text);
            font-size: 15px;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--royal-gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .form-textarea {
            min-height: 150px;
            resize: vertical;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            background: rgba(255, 255, 255, 0.03);
            cursor: pointer;
            position: relative;
        }

        .form-check-input:checked {
            background: var(--royal-gold);
            border-color: var(--royal-gold);
        }

        .form-check-input:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--royal-dark);
            font-weight: bold;
        }

        .form-check-label {
            color: var(--royal-text);
            font-weight: 500;
        }

        .styled-select {
            position: relative;
        }

        .styled-select select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 8px;
            color: #E5E5E5;
            padding: 15px 20px;
            width: 100%;
            font-size: 15px;
            cursor: pointer;
        }

        select.form-control option {
            color: #000 !important;
            background: #fff !important;
            padding: 10px !important;
        }

        .styled-select::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #D4AF37;
            pointer-events: none;
        }

        .royal-tabs {
            display: flex;
            gap: 5px;
            background: rgba(255, 255, 255, 0.03);
            padding: 8px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .tab-btn {
            padding: 15px 30px;
            border: none;
            background: transparent;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            color: var(--royal-text-dark);
            cursor: pointer;
            transition: var(--transition);
            flex: 1;
            text-align: center;
        }

        .tab-btn.active {
            background: rgba(212, 175, 55, 0.1);
            color: var(--royal-gold);
            box-shadow: var(--shadow-sm);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: #1A1A1A;
            border-radius: 16px;
            padding: 30px;
            max-width: 600px;
            width: 90%;
            border: 1px solid rgba(212, 175, 55, 0.2);
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }

        .delete-confirmation {
            text-align: center;
        }

        .delete-confirmation h3 {
            color: #D4AF37;
            font-family: 'Cinzel', serif;
            margin-bottom: 20px;
        }

        .delete-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn-delete-confirm {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-cancel {
            background: rgba(255, 255, 255, 0.05);
            color: #E5E5E5;
            padding: 12px 30px;
            border-radius: 8px;
            border: 1px solid rgba(212, 175, 55, 0.2);
            font-weight: 600;
            cursor: pointer;
        }

        .royal-toast {
            position: fixed;
            top: 30px;
            right: 30px;
            padding: 20px 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 2000;
            animation: slideInRight 0.5s ease-out;
            max-width: 400px;
            border-left: 4px solid;
            background: var(--card-gradient);
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .toast-success {
            border-left-color: var(--royal-success);
        }

        .toast-error {
            border-left-color: var(--royal-danger);
        }

        .toast-icon {
            font-size: 24px;
            color: var(--royal-gold);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .video-preview-container {
            margin: 20px 0;
            background: rgba(0, 0, 0, 0.3);
            border-radius: var(--border-radius-sm);
            overflow: hidden;
        }
        
        .video-thumbnail-preview {
            position: relative;
            display: inline-block;
            width: 100%;
            max-width: 400px;
        }
        
        .video-thumbnail-preview:hover .play-overlay {
            opacity: 1;
        }
        
        .play-overlay {
            position: absolute; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0, 0, 0, 0.5); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            opacity: 0; 
            transition: opacity 0.3s ease;
        }
        
        .play-button {
            width: 60px; 
            height: 60px; 
            background: var(--royal-gold); 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            font-size: 24px;
        }
        
        .duration-badge {
            position: absolute; 
            bottom: 10px; 
            right: 10px; 
            background: rgba(0, 0, 0, 0.7); 
            color: white; 
            padding: 4px 8px; 
            border-radius: 4px; 
            font-size: 12px;
        }

        .video-preview {
            width: 100%;
            max-width: 600px;
            height: 300px;
        }

        .audio-preview {
            width: 100%;
            margin: 20px 0;
        }

        .file-upload-area {
            border: 2px dashed rgba(212, 175, 55, 0.3);
            border-radius: var(--border-radius-sm);
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
            transition: var(--transition);
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: var(--royal-gold);
            background: rgba(212, 175, 55, 0.05);
        }

        .file-upload-area i {
            font-size: 48px;
            color: var(--royal-gold);
            margin-bottom: 15px;
        }

        .file-upload-area p {
            color: var(--royal-text-dark);
            margin-bottom: 10px;
        }

        /* File Upload Preview Styling */
        .file-preview-container {
            margin-bottom: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: var(--border-radius-sm);
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .file-preview {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .file-preview img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        .file-preview span {
            color: var(--royal-text-dark);
            font-size: 13px;
            flex: 1;
        }

        .file-input-wrapper {
            position: relative;
            margin-bottom: 10px;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .file-input-label {
            padding: 15px 20px;
            background: rgba(212, 175, 55, 0.1);
            border: 2px dashed rgba(212, 175, 55, 0.3);
            border-radius: var(--border-radius-sm);
            color: var(--royal-gold);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 60px;
        }

        .file-input-label:hover {
            background: rgba(212, 175, 55, 0.2);
            border-color: var(--royal-gold);
        }

        .file-input-label.has-file {
            background: rgba(16, 185, 129, 0.1);
            border-color: var(--royal-success);
            color: var(--royal-success);
        }

        .file-input-label i {
            font-size: 20px;
        }

        .file-input-label span {
            font-size: 14px;
        }

        .current-file-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: var(--border-radius-sm);
            margin-top: 10px;
            font-size: 13px;
            color: var(--royal-text-dark);
        }

        .current-file-info i {
            color: var(--royal-gold);
        }

        .mobile-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background: var(--royal-gold);
            color: var(--royal-dark);
            border-radius: var(--border-radius);
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            z-index: 1001;
            box-shadow: var(--shadow);
        }

        @media (max-width: 992px) {
            .royal-sidebar {
                transform: translateX(-100%);
            }

            .royal-sidebar.active {
                transform: translateX(0);
            }

            .royal-main {
                margin-left: 0;
            }

            .mobile-toggle {
                display: flex;
            }

            .stats-grid,
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .royal-header {
                flex-direction: column;
                height: auto;
                padding: 20px;
                gap: 20px;
            }

            .header-right {
                width: 100%;
                justify-content: space-between;
            }

            .data-section {
                overflow-x: auto;
            }

            .data-table {
                min-width: 800px;
            }

            .tab-btn {
                padding: 10px 15px;
                font-size: 13px;
            }
        }

        .text-gold {
            color: var(--royal-gold);
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .status-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .status-toggle:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="mobile-toggle" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </div>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <div class="delete-confirmation">
                <h3>Confirm Delete</h3>
                <p id="deleteMessage">Are you sure you want to delete this item? This action cannot be undone.</p>
                <div class="delete-actions">
                    <button class="btn-delete-confirm" id="confirmDelete">Delete</button>
                    <button class="btn-cancel" id="cancelDelete">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="royal-toast toast-<?php echo $message_type; ?>">
            <i
                class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> toast-icon"></i>
            <div>
                <strong class="text-gold"><?php echo $message_type === 'success' ? 'Success!' : 'Error!'; ?></strong>
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <aside class="royal-sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="brand-logo">
                <div class="logo-inner">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
            <h2 class="brand-title">Prep with Daljeet</h2>
            <p class="brand-subtitle">Royal Admin Panel</p>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-title">Navigation</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="admin.php?section=dashboard"
                            class="nav-link <?php echo $current_section == 'dashboard' ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt nav-icon"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin.php?section=courses"
                            class="nav-link <?php echo $current_section == 'courses' ? 'active' : ''; ?>">
                            <i class="fas fa-graduation-cap nav-icon"></i>
                            <span class="nav-text">Courses</span>
                            <span class="nav-badge"><?php echo $stats['total_courses']; ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin.php?section=subjects"
                            class="nav-link <?php echo $current_section == 'subjects' ? 'active' : ''; ?>">
                            <i class="fas fa-book-open nav-icon"></i>
                            <span class="nav-text">Subjects/Modules</span>
                            <span class="nav-badge"><?php echo $stats['total_subjects']; ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin.php?section=books"
                            class="nav-link <?php echo $current_section == 'books' ? 'active' : ''; ?>">
                            <i class="fas fa-book nav-icon"></i>
                            <span class="nav-text">Books/Material</span>
                            <span class="nav-badge"><?php echo $stats['total_books']; ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin.php?section=speaking_examples"
                            class="nav-link <?php echo $current_section == 'speaking_examples' ? 'active' : ''; ?>">
                            <i class="fas fa-microphone-alt nav-icon"></i>
                            <span class="nav-text">Speaking Examples</span>
                            <span class="nav-badge"><?php echo $stats['total_speaking_examples']; ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin.php?section=video_resources"
                            class="nav-link <?php echo $current_section == 'video_resources' ? 'active' : ''; ?>">
                            <i class="fas fa-video nav-icon"></i>
                            <span class="nav-text">Video Resources</span>
                            <span class="nav-badge"><?php echo $stats['total_video_resources']; ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin.php?section=blogs"
                            class="nav-link <?php echo $current_section == 'blogs' ? 'active' : ''; ?>">
                            <i class="fas fa-blog nav-icon"></i>
                            <span class="nav-text">Blog Posts</span>
                            <span class="nav-badge"><?php echo $stats['total_blogs']; ?></span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section">
                <div class="nav-title">System</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="admin.php?section=settings"
                            class="nav-link <?php echo $current_section == 'settings' ? 'active' : ''; ?>">
                            <i class="fas fa-sliders-h nav-icon"></i>
                            <span class="nav-text">Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    <span>AD</span>
                </div>
                <div class="user-info">
                    <h4>Administrator</h4>
                    <span>Super Admin</span>
                </div>
                <a href="logout.php" class="action-icon-btn" style="margin-left: auto;" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </aside>

    <main class="royal-main">
        <header class="royal-header">
            <div class="header-left">
                <h1>
                    <?php
                    switch ($current_section) {
                        case 'courses':
                            echo 'Courses Management';
                            break;
                        case 'subjects':
                            echo 'Subjects Management';
                            break;
                        case 'books':
                            echo 'Books Management';
                            break;
                        case 'speaking_examples':
                            echo 'Speaking Examples';
                            break;
                        case 'video_resources':
                            echo 'Video Resources';
                            break;
                        case 'settings':
                            echo 'Settings';
                            break;
                        default:
                            echo 'Royal Dashboard';
                            break;
                    }
                    ?>
                </h1>
                <p>Manage your education platform with elegance</p>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <button class="action-btn" title="Fullscreen" id="fullscreenBtn">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
                <div class="current-time">
                    <div class="time" id="currentTime"><?php echo date('h:i A'); ?></div>
                    <div class="date" id="currentDate"><?php echo date('F j, Y'); ?></div>
                </div>
            </div>
        </header>

        <div class="content-area">
            <?php if ($current_section == 'dashboard'): ?>
                <section id="dashboard" class="content-section active">
                    <div class="welcome-banner">
                        <h2 class="welcome-title">Welcome Back, Administrator</h2>
                        <p class="welcome-text">Here's an overview of your platform's performance and recent activities.</p>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
                                <div class="stat-trend trend-up"><i
                                        class="fas fa-arrow-up"></i><span><?php echo $stats['total_courses']; ?></span>
                                </div>
                            </div>
                            <div class="stat-value"><?php echo $stats['total_courses']; ?></div>
                            <div class="stat-label">Active Courses</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                                <div class="stat-trend trend-up"><i
                                        class="fas fa-arrow-up"></i><span><?php echo $stats['total_subjects']; ?></span>
                                </div>
                            </div>
                            <div class="stat-value"><?php echo $stats['total_subjects']; ?></div>
                            <div class="stat-label">Subjects/Modules</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon"><i class="fas fa-book"></i></div>
                                <div class="stat-trend trend-up"><i
                                        class="fas fa-arrow-up"></i><span><?php echo $stats['total_books']; ?></span></div>
                            </div>
                            <div class="stat-value"><?php echo $stats['total_books']; ?></div>
                            <div class="stat-label">Study Materials</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon"><i class="fas fa-star"></i></div>
                                <div class="stat-trend trend-up"><i
                                        class="fas fa-arrow-up"></i><span><?php echo $stats['featured_courses']; ?></span>
                                </div>
                            </div>
                            <div class="stat-value"><?php echo $stats['featured_courses']; ?></div>
                            <div class="stat-label">Featured Courses</div>
                        </div>
                    </div>

                    <div class="quick-actions">
                        <a href="admin.php?section=courses" class="action-card">
                            <div class="action-icon"><i class="fas fa-plus"></i></div>
                            <h3 class="action-title">Add New Course</h3>
                            <p class="action-desc">Create a new course offering</p>
                        </a>
                        <a href="admin.php?section=subjects" class="action-card">
                            <div class="action-icon"><i class="fas fa-book-medical"></i></div>
                            <h3 class="action-title">Add Subject/Module</h3>
                            <p class="action-desc">Add new subject to existing course</p>
                        </a>
                        <a href="admin.php?section=books" class="action-card">
                            <div class="action-icon"><i class="fas fa-file-upload"></i></div>
                            <h3 class="action-title">Upload Study Material</h3>
                            <p class="action-desc">Add books, PDFs, and study materials</p>
                        </a>
                        <a href="admin.php?section=settings" class="action-card">
                            <div class="action-icon"><i class="fas fa-cog"></i></div>
                            <h3 class="action-title">System Settings</h3>
                            <p class="action-desc">Configure platform settings</p>
                        </a>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($current_section == 'courses'): ?>
                <section id="courses" class="content-section active">
                    <div class="welcome-banner">
                        <h2 class="welcome-title">Courses Management</h2>
                        <p class="welcome-text">Manage all your courses, create new offerings, and organize them
                            effectively.</p>
                    </div>

                    <div class="form-section">
                        <h3 class="form-title"><?php echo $edit_course ? 'Edit Course' : 'Add New Course'; ?></h3>
                        <form method="POST" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="action"
                                value="<?php echo $edit_course ? 'edit_course' : 'add_course'; ?>">
                            <?php if ($edit_course): ?>
                                <input type="hidden" name="id" value="<?php echo $edit_course['id']; ?>">
                                <?php if ($edit_course['cover_image']): ?>
                                    <input type="hidden" name="cover_image_current"
                                        value="<?php echo htmlspecialchars($edit_course['cover_image']); ?>">
                                <?php endif; ?>
                            <?php endif; ?>

                            <div class="row"
                                style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                <div class="form-group">
                                    <label for="course_name" class="form-label">Course Name *</label>
                                    <input type="text" id="course_name" name="name" class="form-control" required
                                        placeholder="e.g., IELTS Coaching" autocomplete="off"
                                        value="<?php echo $edit_course ? htmlspecialchars($edit_course['name']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="course_icon" class="form-label">Icon Class</label>
                                    <input type="text" id="course_icon" name="icon" class="form-control"
                                        placeholder="fas fa-book" autocomplete="off"
                                        value="<?php echo $edit_course ? htmlspecialchars($edit_course['icon']) : 'fas fa-book'; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="short_description" class="form-label">Short Description</label>
                                <input type="text" id="short_description" name="short_description" class="form-control"
                                    autocomplete="off" placeholder="Brief description (max 200 characters)"
                                    value="<?php echo $edit_course ? htmlspecialchars($edit_course['short_description']) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="full_description" class="form-label">Full Description</label>
                                <textarea id="full_description" name="full_description" class="form-control form-textarea"
                                    autocomplete="off"
                                    placeholder="Detailed course description..."><?php echo $edit_course ? htmlspecialchars($edit_course['full_description']) : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="cover_image" class="form-label">Cover Image</label>
                                <?php if ($edit_course && $edit_course['cover_image']): ?>
                                    <div class="file-preview-container">
                                        <div class="file-preview">
                                            <img src="<?php echo htmlspecialchars($edit_course['cover_image']); ?>"
                                                alt="Current Cover Image">
                                            <span>Current Image: <?php echo basename($edit_course['cover_image']); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="file-input-wrapper">
                                    <input type="file" id="cover_image" name="cover_image" class="form-control"
                                        accept="image/*">
                                    <label for="cover_image" class="file-input-label">
                                        <i class="fas fa-upload"></i>
                                        <span><?php echo ($edit_course && $edit_course['cover_image']) ? 'Change Cover Image' : 'Upload Cover Image'; ?></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row"
                                style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                <div class="form-group">
                                    <label for="course_status" class="form-label">Status</label>
                                    <div class="styled-select">
                                        <select id="course_status" name="status" class="form-control" autocomplete="off">
                                            <option value="active" <?php echo ($edit_course && $edit_course['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                            <option value="inactive" <?php echo ($edit_course && $edit_course['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                            <option value="coming_soon" <?php echo ($edit_course && $edit_course['status'] == 'coming_soon') ? 'selected' : ''; ?>>Coming Soon
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" id="sort_order" name="sort_order" class="form-control"
                                        autocomplete="off"
                                        value="<?php echo $edit_course ? $edit_course['sort_order'] : '0'; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Options</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input"
                                            autocomplete="off" <?php echo ($edit_course && $edit_course['is_featured']) ? 'checked' : ''; ?>>
                                        <label for="is_featured" class="form-check-label">Featured Course</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_popular" id="is_popular" class="form-check-input"
                                            autocomplete="off" <?php echo ($edit_course && $edit_course['is_popular']) ? 'checked' : ''; ?>>
                                        <label for="is_popular" class="form-check-label">Mark as Popular</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-gold">
                                <i class="fas fa-<?php echo $edit_course ? 'save' : 'plus-circle'; ?>"></i>
                                <?php echo $edit_course ? 'Update Course' : 'Add Course'; ?>
                            </button>
                            <?php if ($edit_course): ?>
                                <a href="admin.php?section=courses" class="btn btn-dark" style="margin-left: 10px;"><i
                                        class="fas fa-times"></i> Cancel</a>
                            <?php endif; ?>
                        </form>
                    </div>

                    <div class="data-section">
                        <div class="section-header">
                            <h3 class="section-title"><i class="fas fa-list"></i> All Courses</h3>
                            <div class="section-actions">
                                <button class="btn btn-dark" onclick="window.location.href='admin.php?section=courses'"><i
                                        class="fas fa-sync-alt"></i> Refresh</button>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Course</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Popular</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $all_courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY sort_order DESC, created_at DESC");
                                    while ($course = mysqli_fetch_assoc($all_courses)):
                                        ?>
                                        <tr>
                                            <td>#<?php echo $course['id']; ?></td>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 15px;">
                                                    <div
                                                        style="width: 40px; height: 40px; background: rgba(212, 175, 55, 0.1); border-radius: var(--border-radius-sm); display: flex; align-items: center; justify-content: center; color: var(--royal-gold);">
                                                        <i class="<?php echo htmlspecialchars($course['icon']); ?>"></i>
                                                    </div>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($course['name']); ?></strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button
                                                    class="status-toggle <?php echo $course['status'] === 'active' ? 'status-active' : ($course['status'] === 'coming_soon' ? 'status-pending' : 'status-inactive'); ?>"
                                                    onclick="toggleStatus('course', <?php echo $course['id']; ?>, '<?php echo $course['status'] === 'active' ? 'inactive' : ($course['status'] === 'inactive' ? 'coming_soon' : 'active'); ?>')">
                                                    <i
                                                        class="fas fa-<?php echo $course['status'] === 'active' ? 'check-circle' : ($course['status'] === 'coming_soon' ? 'clock' : 'times-circle'); ?>"></i>
                                                    <?php echo ucfirst(str_replace('_', ' ', $course['status'])); ?>
                                                </button>
                                            </td>
                                            <td><?php if ($course['is_featured']): ?><i
                                                        class="fas fa-star text-gold"></i><?php endif; ?></td>
                                            <td><?php if ($course['is_popular']): ?><i
                                                        class="fas fa-fire text-gold"></i><?php endif; ?></td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="admin.php?section=courses&action=edit&type=course&id=<?php echo $course['id']; ?>"
                                                        class="action-icon-btn" title="Edit"><i class="fas fa-edit"></i></a>
                                                    <button class="action-icon-btn"
                                                        onclick="confirmDelete('course', <?php echo $course['id']; ?>, '<?php echo htmlspecialchars($course['name']); ?>')"
                                                        title="Delete"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($current_section == 'subjects'): ?>
                <section id="subjects" class="content-section active">
                    <div class="welcome-banner">
                        <h2 class="welcome-title">Subjects/Modules Management</h2>
                        <p class="welcome-text">Manage subjects and modules linked to your courses.</p>
                    </div>

                    <div class="royal-tabs">
                        <button class="tab-btn active"
                            data-tab="manage-subjects"><?php echo $edit_subject ? 'Edit Subject' : 'Add New Subject'; ?></button>
                        <button class="tab-btn" data-tab="all-subjects">All Subjects</button>
                    </div>

                    <div id="manage-subjects" class="tab-content active">
                        <div class="form-section">
                            <h3 class="form-title">
                                <?php echo $edit_subject ? 'Edit Subject/Module' : 'Add New Subject/Module'; ?>
                            </h3>
                            <form method="POST" autocomplete="off">
                                <input type="hidden" name="action"
                                    value="<?php echo $edit_subject ? 'edit_subject' : 'add_subject'; ?>">
                                <?php if ($edit_subject): ?>
                                    <input type="hidden" name="id" value="<?php echo $edit_subject['id']; ?>">
                                <?php endif; ?>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="subject_course_id" class="form-label">Select Course *</label>
                                        <div class="styled-select">
                                            <select id="subject_course_id" name="course_id" class="form-control" required
                                                autocomplete="off">
                                                <option value="">-- Select Course --</option>
                                                <?php
                                                $active_courses = mysqli_query($conn, "SELECT * FROM courses WHERE status='active' ORDER BY name");
                                                while ($course = mysqli_fetch_assoc($active_courses)):
                                                    ?>
                                                    <option value="<?php echo $course['id']; ?>" <?php echo ($edit_subject && $edit_subject['course_id'] == $course['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($course['name']); ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="subject_name" class="form-label">Subject Name *</label>
                                        <input type="text" id="subject_name" name="subject_name" class="form-control"
                                            required autocomplete="off" placeholder="e.g., IELTS Academic Package"
                                            value="<?php echo $edit_subject ? htmlspecialchars($edit_subject['subject_name']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="duration" class="form-label">Duration</label>
                                        <input type="number" id="duration" name="duration" class="form-control"
                                            placeholder="4" autocomplete="off"
                                            value="<?php echo $edit_subject ? htmlspecialchars($edit_subject['duration']) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="duration_type" class="form-label">Duration Type</label>
                                        <div class="styled-select">
                                            <select id="duration_type" name="duration_type" class="form-control"
                                                autocomplete="off">
                                                <option value="weeks" <?php echo ($edit_subject && $edit_subject['duration_type'] == 'weeks') ? 'selected' : ''; ?>>Weeks
                                                </option>
                                                <option value="hours" <?php echo ($edit_subject && $edit_subject['duration_type'] == 'hours') ? 'selected' : ''; ?>>Hours
                                                </option>
                                                <option value="months" <?php echo ($edit_subject && $edit_subject['duration_type'] == 'months') ? 'selected' : ''; ?>>Months
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="price" class="form-label">Price (₹) *</label>
                                        <input type="number" id="price" name="price" class="form-control" step="0.01"
                                            min="0" required autocomplete="off"
                                            value="<?php echo $edit_subject ? $edit_subject['price'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="discount_price" class="form-label">Discount Price (₹)</label>
                                        <input type="number" id="discount_price" name="discount_price" class="form-control"
                                            step="0.01" min="0" autocomplete="off"
                                            value="<?php echo $edit_subject ? $edit_subject['discount_price'] : ''; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea id="description" name="description" class="form-control form-textarea"
                                        autocomplete="off"><?php echo $edit_subject ? htmlspecialchars($edit_subject['description']) : ''; ?></textarea>
                                </div>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="highlights" class="form-label">Highlights</label>
                                        <textarea id="highlights" name="highlights" class="form-control"
                                            autocomplete="off"><?php echo $edit_subject ? htmlspecialchars($edit_subject['highlights']) : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="what_you_learn" class="form-label">What You Learn</label>
                                        <textarea id="what_you_learn" name="what_you_learn" class="form-control"
                                            autocomplete="off"><?php echo $edit_subject ? htmlspecialchars($edit_subject['what_you_learn']) : ''; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="prerequisites" class="form-label">Prerequisites</label>
                                    <input type="text" id="prerequisites" name="prerequisites" class="form-control"
                                        autocomplete="off"
                                        value="<?php echo $edit_subject ? htmlspecialchars($edit_subject['prerequisites']) : ''; ?>">
                                </div>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="subject_status" class="form-label">Status</label>
                                        <div class="styled-select">
                                            <select id="subject_status" name="status" class="form-control"
                                                autocomplete="off">
                                                <option value="active" <?php echo ($edit_subject && $edit_subject['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                                <option value="inactive" <?php echo ($edit_subject && $edit_subject['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="display_order" class="form-label">Display Order</label>
                                        <input type="number" id="display_order" name="display_order" class="form-control"
                                            autocomplete="off"
                                            value="<?php echo $edit_subject ? $edit_subject['display_order'] : '0'; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Options</label>
                                        <div class="form-check">
                                            <input type="checkbox" name="is_bestseller" id="is_bestseller"
                                                class="form-check-input" autocomplete="off" <?php echo ($edit_subject && $edit_subject['is_bestseller']) ? 'checked' : ''; ?>>
                                            <label for="is_bestseller" class="form-check-label">Bestseller</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="is_recommended" id="is_recommended"
                                                class="form-check-input" autocomplete="off" <?php echo ($edit_subject && $edit_subject['is_recommended']) ? 'checked' : ''; ?>>
                                            <label for="is_recommended" class="form-check-label">Recommended</label>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-gold">
                                    <i class="fas fa-<?php echo $edit_subject ? 'save' : 'plus-circle'; ?>"></i>
                                    <?php echo $edit_subject ? 'Update Subject' : 'Add Subject'; ?>
                                </button>
                                <?php if ($edit_subject): ?>
                                    <a href="admin.php?section=subjects" class="btn btn-dark" style="margin-left: 10px;"><i
                                            class="fas fa-times"></i> Cancel</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <div id="all-subjects" class="tab-content">
                        <div class="data-section">
                            <div class="section-header">
                                <h3 class="section-title"><i class="fas fa-list"></i> All Subjects</h3>
                                <div class="section-actions">
                                    <label for="subjectSearch" style="display:none;">Search</label>
                                    <input type="text" class="form-control" placeholder="Search..." style="width: 200px;"
                                        id="subjectSearch" autocomplete="off">
                                </div>
                            </div>
                            <div class="table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Subject</th>
                                            <th>Course</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $all_subjects = mysqli_query($conn, "SELECT s.*, c.name as course_name FROM subjects s LEFT JOIN courses c ON s.course_id = c.id ORDER BY s.display_order DESC, s.created_at DESC");
                                        while ($subject = mysqli_fetch_assoc($all_subjects)):
                                            ?>
                                            <tr>
                                                <td>#<?php echo $subject['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($subject['subject_name']); ?></strong>
                                                    <?php if ($subject['is_bestseller']): ?>
                                                        <span class="status-badge status-active"
                                                            style="font-size: 10px;">Bestseller</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($subject['course_name']); ?></td>
                                                <td>
                                                    <strong
                                                        class="text-gold"><?php echo formatCurrency($subject['discount_price'] ?: $subject['price'], $subject['currency']); ?></strong>
                                                </td>
                                                <td>
                                                    <button
                                                        class="status-toggle <?php echo $subject['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>"
                                                        onclick="toggleStatus('subject', <?php echo $subject['id']; ?>, '<?php echo $subject['status'] === 'active' ? 'inactive' : 'active'; ?>')">
                                                        <i
                                                            class="fas fa-<?php echo $subject['status'] === 'active' ? 'check-circle' : 'times-circle'; ?>"></i>
                                                        <?php echo ucfirst($subject['status']); ?>
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="table-actions">
                                                        <a href="admin.php?section=subjects&action=edit&type=subject&id=<?php echo $subject['id']; ?>"
                                                            class="action-icon-btn" title="Edit"><i class="fas fa-edit"></i></a>
                                                        <button class="action-icon-btn"
                                                            onclick="confirmDelete('subject', <?php echo $subject['id']; ?>, '<?php echo htmlspecialchars($subject['subject_name']); ?>')"
                                                            title="Delete"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($current_section == 'books'): ?>
                <section id="books" class="content-section active">
                    <div class="welcome-banner">
                        <h2 class="welcome-title">Books & Study Materials</h2>
                        <p class="welcome-text">Manage books, PDFs, and study materials linked to subjects or as independent
                            free resources.</p>
                    </div>

                    <div class="royal-tabs">
                        <button class="tab-btn active"
                            data-tab="manage-books"><?php echo $edit_book ? 'Edit Material' : 'Add New Material'; ?></button>
                        <button class="tab-btn" data-tab="all-books">All Materials</button>
                        <button class="tab-btn" data-tab="free-materials">Free Study Materials</button>
                    </div>

                    <div id="manage-books" class="tab-content active">
                        <div class="form-section">
                            <h3 class="form-title">
                                <?php echo $edit_book ? 'Edit Book/Study Material' : 'Add New Book/Study Material'; ?>
                            </h3>
                            <form method="POST" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="action"
                                    value="<?php echo $edit_book ? 'edit_book' : 'add_book'; ?>">
                                <?php if ($edit_book): ?>
                                    <input type="hidden" name="id" value="<?php echo $edit_book['id']; ?>">
                                    <?php if ($edit_book['file_url']): ?>
                                        <input type="hidden" name="file_url_current"
                                            value="<?php echo htmlspecialchars($edit_book['file_url']); ?>">
                                        <input type="hidden" name="file_size_current"
                                            value="<?php echo htmlspecialchars($edit_book['file_size']); ?>">
                                    <?php endif; ?>
                                    <?php if ($edit_book['cover_image']): ?>
                                        <input type="hidden" name="cover_image_current"
                                            value="<?php echo htmlspecialchars($edit_book['cover_image']); ?>">
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="book_subject_id" class="form-label">Select Subject (Optional for
                                            Independent Materials)</label>
                                        <div class="styled-select">
                                            <select id="book_subject_id" name="subject_id" class="form-control"
                                                autocomplete="off">
                                                <option value="">-- Independent Material --</option>
                                                <?php
                                                $active_subjects = mysqli_query($conn, "SELECT s.*, c.name as course_name FROM subjects s LEFT JOIN courses c ON s.course_id = c.id WHERE s.status='active' ORDER BY s.subject_name");
                                                while ($subject = mysqli_fetch_assoc($active_subjects)):
                                                    ?>
                                                    <option value="<?php echo $subject['id']; ?>" <?php echo ($edit_book && $edit_book['subject_id'] == $subject['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($subject['course_name'] . ' - ' . $subject['subject_name']); ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="book_title" class="form-label">Title *</label>
                                        <input type="text" id="book_title" name="title" class="form-control" required
                                            autocomplete="off" placeholder="e.g., IELTS Speaking Guide"
                                            value="<?php echo $edit_book ? htmlspecialchars($edit_book['title']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="author" class="form-label">Author</label>
                                        <input type="text" id="author" name="author" class="form-control"
                                            placeholder="Author name" autocomplete="off"
                                            value="<?php echo $edit_book ? htmlspecialchars($edit_book['author']) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="publisher" class="form-label">Publisher</label>
                                        <input type="text" id="publisher" name="publisher" class="form-control"
                                            autocomplete="off" placeholder="Publisher name"
                                            value="<?php echo $edit_book ? htmlspecialchars($edit_book['publisher']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="edition" class="form-label">Edition</label>
                                        <input type="text" id="edition" name="edition" class="form-control"
                                            autocomplete="off"
                                            value="<?php echo $edit_book ? htmlspecialchars($edit_book['edition']) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="isbn" class="form-label">ISBN</label>
                                        <input type="text" id="isbn" name="isbn" class="form-control" autocomplete="off"
                                            value="<?php echo $edit_book ? htmlspecialchars($edit_book['isbn']) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="pages" class="form-label">Pages</label>
                                        <input type="number" id="pages" name="pages" class="form-control" autocomplete="off"
                                            value="<?php echo $edit_book ? htmlspecialchars($edit_book['pages']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="book_file" class="form-label">Upload Book File (PDF, DOC, etc.)</label>
                                    <?php if ($edit_book && $edit_book['file_url']): ?>
                                        <div class="file-preview-container">
                                            <div class="file-preview">
                                                <i class="fas fa-file-pdf"
                                                    style="font-size: 30px; color: var(--royal-gold);"></i>
                                                <span>Current File: <?php echo basename($edit_book['file_url']); ?>
                                                    (<?php echo $edit_book['file_size']; ?>)</span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="file-input-wrapper">
                                        <input type="file" id="book_file" name="book_file" class="form-control"
                                            accept=".pdf,.doc,.docx,.txt,.ppt,.pptx">
                                        <label for="book_file" class="file-input-label">
                                            <i class="fas fa-upload"></i>
                                            <span><?php echo ($edit_book && $edit_book['file_url']) ? 'Change Book File' : 'Upload Book File'; ?></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="file_format" class="form-label">File Format</label>
                                    <input type="text" id="file_format" name="file_format" class="form-control"
                                        placeholder="PDF" autocomplete="off"
                                        value="<?php echo $edit_book ? htmlspecialchars($edit_book['file_format']) : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="cover_image" class="form-label">Cover Image</label>
                                    <?php if ($edit_book && $edit_book['cover_image']): ?>
                                        <div class="file-preview-container">
                                            <div class="file-preview">
                                                <img src="<?php echo htmlspecialchars($edit_book['cover_image']); ?>"
                                                    alt="Current Cover Image">
                                                <span>Current Image: <?php echo basename($edit_book['cover_image']); ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="file-input-wrapper">
                                        <input type="file" id="cover_image" name="cover_image" class="form-control"
                                            accept="image/*">
                                        <label for="cover_image" class="file-input-label">
                                            <i class="fas fa-upload"></i>
                                            <span><?php echo ($edit_book && $edit_book['cover_image']) ? 'Change Cover Image' : 'Upload Cover Image'; ?></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="book_description" class="form-label">Description</label>
                                    <textarea id="book_description" name="description" class="form-control form-textarea"
                                        autocomplete="off"><?php echo $edit_book ? htmlspecialchars($edit_book['description']) : ''; ?></textarea>
                                </div>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="language" class="form-label">Language</label>
                                        <input type="text" id="language" name="language" class="form-control"
                                            autocomplete="off"
                                            value="<?php echo $edit_book ? htmlspecialchars($edit_book['language']) : 'English'; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="book_price" class="form-label">Price (₹)</label>
                                        <input type="number" id="book_price" name="price" class="form-control" step="0.01"
                                            min="0" autocomplete="off"
                                            value="<?php echo $edit_book ? $edit_book['price'] : '0'; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="book_status" class="form-label">Status</label>
                                        <div class="styled-select">
                                            <select id="book_status" name="status" class="form-control" autocomplete="off">
                                                <option value="published" <?php echo ($edit_book && $edit_book['status'] == 'published') ? 'selected' : ''; ?>>Published
                                                </option>
                                                <option value="draft" <?php echo ($edit_book && $edit_book['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Options</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_independent" id="is_independent"
                                            class="form-check-input" autocomplete="off" <?php echo ($edit_book && $edit_book['is_independent']) ? 'checked' : ''; ?>>
                                        <label for="is_independent" class="form-check-label">Independent Material (Not
                                            linked to subject)</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_free" id="is_free" class="form-check-input"
                                            autocomplete="off" <?php echo ($edit_book && $edit_book['is_free']) ? 'checked' : ''; ?>>
                                        <label for="is_free" class="form-check-label">Free Material</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_featured" id="is_featured_book"
                                            class="form-check-input" autocomplete="off" <?php echo ($edit_book && $edit_book['is_featured']) ? 'checked' : ''; ?>>
                                        <label for="is_featured_book" class="form-check-label">Featured Material</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_recommended" id="is_recommended_book"
                                            class="form-check-input" autocomplete="off" <?php echo ($edit_book && $edit_book['is_recommended']) ? 'checked' : ''; ?>>
                                        <label for="is_recommended_book" class="form-check-label">Recommended</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-gold">
                                    <i class="fas fa-<?php echo $edit_book ? 'save' : 'plus-circle'; ?>"></i>
                                    <?php echo $edit_book ? 'Update Book' : 'Add Book'; ?>
                                </button>
                                <?php if ($edit_book): ?>
                                    <a href="admin.php?section=books" class="btn btn-dark" style="margin-left: 10px;"><i
                                            class="fas fa-times"></i> Cancel</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <div id="all-books" class="tab-content">
                        <div class="data-section">
                            <div class="section-header">
                                <h3 class="section-title"><i class="fas fa-list"></i> All Materials</h3>
                                <div class="section-actions">
                                    <label for="bookSearch" style="display:none;">Search</label>
                                    <input type="text" class="form-control" placeholder="Search..." style="width: 200px;"
                                        id="bookSearch" autocomplete="off">
                                </div>
                            </div>
                            <div class="table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $all_books = mysqli_query($conn, "SELECT b.*, s.subject_name, c.name as course_name FROM books b LEFT JOIN subjects s ON b.subject_id = s.id LEFT JOIN courses c ON s.course_id = c.id ORDER BY b.created_at DESC");
                                        while ($book = mysqli_fetch_assoc($all_books)):
                                            $type = $book['is_independent'] ? 'Independent' : ($book['subject_id'] ? 'Subject Linked' : 'Independent');
                                            ?>
                                            <tr>
                                                <td>#<?php echo $book['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($book['title']); ?></strong>
                                                    <?php if ($book['edition']): ?>
                                                        <div style="font-size: 12px; color: var(--royal-text-dark);">
                                                            <?php echo htmlspecialchars($book['edition']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($book['is_independent']): ?>
                                                        <span class="status-badge status-active"
                                                            style="background: rgba(59, 130, 246, 0.1); color: var(--royal-info);">Independent</span>
                                                    <?php elseif ($book['subject_id']): ?>
                                                        <div style="font-size: 13px;">
                                                            <?php echo htmlspecialchars($book['course_name']); ?>
                                                        </div>
                                                        <div style="font-size: 12px; color: var(--royal-text-dark);">
                                                            <?php echo htmlspecialchars($book['subject_name']); ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="status-badge status-active">Independent</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($book['is_free']): ?>
                                                        <span class="status-badge status-active">Free</span>
                                                    <?php else: ?>
                                                        <strong
                                                            class="text-gold"><?php echo formatCurrency($book['price'], 'INR'); ?></strong>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button
                                                        class="status-toggle <?php echo $book['status'] === 'published' ? 'status-active' : 'status-inactive'; ?>"
                                                        onclick="toggleStatus('book', <?php echo $book['id']; ?>, '<?php echo $book['status'] === 'published' ? 'draft' : 'published'; ?>')">
                                                        <i
                                                            class="fas fa-<?php echo $book['status'] === 'published' ? 'check-circle' : 'times-circle'; ?>"></i>
                                                        <?php echo ucfirst($book['status']); ?>
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="table-actions">
                                                        <a href="admin.php?section=books&action=edit&type=book&id=<?php echo $book['id']; ?>"
                                                            class="action-icon-btn" title="Edit"><i class="fas fa-edit"></i></a>
                                                        <button class="action-icon-btn"
                                                            onclick="confirmDelete('book', <?php echo $book['id']; ?>, '<?php echo htmlspecialchars($book['title']); ?>')"
                                                            title="Delete"><i class="fas fa-trash"></i></button>
                                                        <?php if ($book['file_url']): ?>
                                                            <a href="<?php echo htmlspecialchars($book['file_url']); ?>"
                                                                target="_blank" class="action-icon-btn" title="Download"><i
                                                                    class="fas fa-download"></i></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="free-materials" class="tab-content">
                        <div class="data-section">
                            <div class="section-header">
                                <h3 class="section-title"><i class="fas fa-gift"></i> Free Study Materials</h3>
                            </div>
                            <div class="table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>File</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $free_books = mysqli_query($conn, "SELECT * FROM books WHERE is_free = 1 ORDER BY created_at DESC");
                                        while ($book = mysqli_fetch_assoc($free_books)):
                                            ?>
                                            <tr>
                                                <td>#<?php echo $book['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($book['title']); ?></strong>
                                                    <?php if ($book['edition']): ?>
                                                        <div style="font-size: 12px; color: var(--royal-text-dark);">
                                                            <?php echo htmlspecialchars($book['edition']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                                <td>
                                                    <?php if ($book['file_url']): ?>
                                                        <a href="<?php echo htmlspecialchars($book['file_url']); ?>" target="_blank"
                                                            class="btn btn-dark btn-sm"><i class="fas fa-download"></i> Download</a>
                                                    <?php else: ?>
                                                        <span style="color: var(--royal-text-dark);">No file</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button
                                                        class="status-toggle <?php echo $book['status'] === 'published' ? 'status-active' : 'status-inactive'; ?>"
                                                        onclick="toggleStatus('book', <?php echo $book['id']; ?>, '<?php echo $book['status'] === 'published' ? 'draft' : 'published'; ?>')">
                                                        <i
                                                            class="fas fa-<?php echo $book['status'] === 'published' ? 'check-circle' : 'times-circle'; ?>"></i>
                                                        <?php echo ucfirst($book['status']); ?>
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="table-actions">
                                                        <a href="admin.php?section=books&action=edit&type=book&id=<?php echo $book['id']; ?>"
                                                            class="action-icon-btn" title="Edit"><i class="fas fa-edit"></i></a>
                                                        <button class="action-icon-btn"
                                                            onclick="confirmDelete('book', <?php echo $book['id']; ?>, '<?php echo htmlspecialchars($book['title']); ?>')"
                                                            title="Delete"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                        <?php if (mysqli_num_rows($free_books) == 0): ?>
                                            <tr>
                                                <td colspan="6" style="text-align: center; padding: 40px;">
                                                    <i class="fas fa-inbox"
                                                        style="font-size: 48px; color: var(--royal-text-dark); margin-bottom: 15px;"></i>
                                                    <p>No free study materials found. Add some to display here!</p>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($current_section == 'speaking_examples'): ?>
                <section id="speaking_examples" class="content-section active">
                    <div class="welcome-banner">
                        <h2 class="welcome-title">Speaking Examples Management</h2>
                        <p class="welcome-text">Manage audio files and transcripts for speaking practice examples.</p>
                    </div>

                    <div class="royal-tabs">
                        <button class="tab-btn active"
                            data-tab="manage-speaking"><?php echo $edit_speaking_example ? 'Edit Speaking Example' : 'Add New Speaking Example'; ?></button>
                        <button class="tab-btn" data-tab="all-speaking">All Speaking Examples</button>
                    </div>

                    <div id="manage-speaking" class="tab-content active">
                        <div class="form-section">
                            <h3 class="form-title">
                                <?php echo $edit_speaking_example ? 'Edit Speaking Example' : 'Add New Speaking Example'; ?>
                            </h3>
                            <form method="POST" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="action"
                                    value="<?php echo $edit_speaking_example ? 'edit_speaking_example' : 'add_speaking_example'; ?>">
                                <?php if ($edit_speaking_example): ?>
                                    <input type="hidden" name="id" value="<?php echo $edit_speaking_example['id']; ?>">
                                    <?php if ($edit_speaking_example['audio_file']): ?>
                                        <input type="hidden" name="audio_file_current"
                                            value="<?php echo htmlspecialchars($edit_speaking_example['audio_file']); ?>">
                                        <input type="hidden" name="file_size_current"
                                            value="<?php echo htmlspecialchars($edit_speaking_example['file_size']); ?>">
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="speaking_title" class="form-label">Title *</label>
                                        <input type="text" id="speaking_title" name="title" class="form-control" required
                                            autocomplete="off" placeholder="e.g., IELTS Speaking Part 2 Sample"
                                            value="<?php echo $edit_speaking_example ? htmlspecialchars($edit_speaking_example['title']) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="speaking_level" class="form-label">Level</label>
                                        <div class="styled-select">
                                            <select id="speaking_level" name="level" class="form-control"
                                                autocomplete="off">
                                                <option value="beginner" <?php echo ($edit_speaking_example && $edit_speaking_example['level'] == 'beginner') ? 'selected' : ''; ?>>
                                                    Beginner</option>
                                                <option value="intermediate" <?php echo ($edit_speaking_example && $edit_speaking_example['level'] == 'intermediate') ? 'selected' : ''; ?>>
                                                    Intermediate</option>
                                                <option value="advanced" <?php echo ($edit_speaking_example && $edit_speaking_example['level'] == 'advanced') ? 'selected' : ''; ?>>
                                                    Advanced</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="speaking_description" class="form-label">Description</label>
                                    <textarea id="speaking_description" name="description"
                                        class="form-control form-textarea"
                                        autocomplete="off"><?php echo $edit_speaking_example ? htmlspecialchars($edit_speaking_example['description']) : ''; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="audio_file" class="form-label">Upload Audio File (MP3, WAV, etc.) *</label>
                                    <?php if ($edit_speaking_example && $edit_speaking_example['audio_file']): ?>
                                        <div class="file-preview-container">
                                            <div class="file-preview">
                                                <i class="fas fa-file-audio"
                                                    style="font-size: 30px; color: var(--royal-gold);"></i>
                                                <span>Current Audio:
                                                    <?php echo basename($edit_speaking_example['audio_file']); ?>
                                                    (<?php echo $edit_speaking_example['file_size']; ?>)</span>
                                            </div>
                                            <div class="audio-preview">
                                                <audio controls class="audio-preview">
                                                    <source
                                                        src="<?php echo htmlspecialchars($edit_speaking_example['audio_file']); ?>"
                                                        type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="file-input-wrapper">
                                        <input type="file" id="audio_file" name="audio_file" class="form-control"
                                            accept="audio/*">
                                        <label for="audio_file" class="file-input-label">
                                            <i class="fas fa-upload"></i>
                                            <span><?php echo ($edit_speaking_example && $edit_speaking_example['audio_file']) ? 'Change Audio File' : 'Upload Audio File'; ?></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="duration" class="form-label">Duration</label>
                                        <input type="text" id="duration" name="duration" class="form-control"
                                            placeholder="2:30" autocomplete="off"
                                            value="<?php echo $edit_speaking_example ? htmlspecialchars($edit_speaking_example['duration']) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="file_size" class="form-label">File Size</label>
                                        <input type="text" id="file_size" name="file_size" class="form-control"
                                            placeholder="5.2 MB" autocomplete="off"
                                            value="<?php echo $edit_speaking_example ? htmlspecialchars($edit_speaking_example['file_size']) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="speaking_status" class="form-label">Status</label>
                                        <div class="styled-select">
                                            <select id="speaking_status" name="status" class="form-control"
                                                autocomplete="off">
                                                <option value="published" <?php echo ($edit_speaking_example && $edit_speaking_example['status'] == 'published') ? 'selected' : ''; ?>>
                                                    Published</option>
                                                <option value="draft" <?php echo ($edit_speaking_example && $edit_speaking_example['status'] == 'draft') ? 'selected' : ''; ?>>Draft
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="transcript" class="form-label">Transcript</label>
                                    <textarea id="transcript" name="transcript" class="form-control form-textarea" rows="6"
                                        placeholder="Full transcript of the audio..."><?php echo $edit_speaking_example ? htmlspecialchars($edit_speaking_example['transcript']) : ''; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="tags" class="form-label">Tags (comma separated)</label>
                                    <input type="text" id="tags" name="tags" class="form-control"
                                        placeholder="IELTS, Speaking, Part 2, Sample" autocomplete="off"
                                        value="<?php echo $edit_speaking_example ? htmlspecialchars($edit_speaking_example['tags']) : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Options</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_featured" id="is_featured_speaking"
                                            class="form-check-input" autocomplete="off" <?php echo ($edit_speaking_example && $edit_speaking_example['is_featured']) ? 'checked' : ''; ?>>
                                        <label for="is_featured_speaking" class="form-check-label">Featured Example</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-gold">
                                    <i class="fas fa-<?php echo $edit_speaking_example ? 'save' : 'plus-circle'; ?>"></i>
                                    <?php echo $edit_speaking_example ? 'Update Speaking Example' : 'Add Speaking Example'; ?>
                                </button>
                                <?php if ($edit_speaking_example): ?>
                                    <a href="admin.php?section=speaking_examples" class="btn btn-dark"
                                        style="margin-left: 10px;"><i class="fas fa-times"></i> Cancel</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <div id="all-speaking" class="tab-content">
                        <div class="data-section">
                            <div class="section-header">
                                <h3 class="section-title"><i class="fas fa-list"></i> All Speaking Examples</h3>
                                <div class="section-actions">
                                    <label for="speakingSearch" style="display:none;">Search</label>
                                    <input type="text" class="form-control" placeholder="Search..." style="width: 200px;"
                                        id="speakingSearch" autocomplete="off">
                                </div>
                            </div>
                            <div class="table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Level</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $all_speaking = mysqli_query($conn, "SELECT * FROM speaking_examples ORDER BY created_at DESC");
                                        while ($example = mysqli_fetch_assoc($all_speaking)):
                                            ?>
                                            <tr>
                                                <td>#<?php echo $example['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($example['title']); ?></strong>
                                                    <?php if ($example['is_featured']): ?>
                                                        <span class="status-badge status-active"
                                                            style="font-size: 10px;">Featured</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span
                                                        class="status-badge <?php echo $example['level'] === 'beginner' ? 'status-active' : ($example['level'] === 'intermediate' ? 'status-pending' : 'status-inactive'); ?>">
                                                        <?php echo ucfirst($example['level']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($example['duration']); ?></td>
                                                <td>
                                                    <button
                                                        class="status-toggle <?php echo $example['status'] === 'published' ? 'status-active' : 'status-inactive'; ?>"
                                                        onclick="toggleStatus('speaking_example', <?php echo $example['id']; ?>, '<?php echo $example['status'] === 'published' ? 'draft' : 'published'; ?>')">
                                                        <i
                                                            class="fas fa-<?php echo $example['status'] === 'published' ? 'check-circle' : 'times-circle'; ?>"></i>
                                                        <?php echo ucfirst($example['status']); ?>
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="table-actions">
                                                        <a href="admin.php?section=speaking_examples&action=edit&type=speaking_example&id=<?php echo $example['id']; ?>"
                                                            class="action-icon-btn" title="Edit"><i class="fas fa-edit"></i></a>
                                                        <button class="action-icon-btn"
                                                            onclick="confirmDelete('speaking_example', <?php echo $example['id']; ?>, '<?php echo htmlspecialchars($example['title']); ?>')"
                                                            title="Delete"><i class="fas fa-trash"></i></button>
                                                        <?php if ($example['audio_file']): ?>
                                                            <a href="<?php echo htmlspecialchars($example['audio_file']); ?>"
                                                                target="_blank" class="action-icon-btn" title="Play"><i
                                                                    class="fas fa-play"></i></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($current_section == 'video_resources'): ?>
                <section id="video_resources" class="content-section active">
                    <div class="welcome-banner">
                        <h2 class="welcome-title">Video Resources Management</h2>
                        <p class="welcome-text">Manage video tutorials, lessons, and other video resources from YouTube and
                            other platforms.</p>
                    </div>

                    <div class="royal-tabs">
                        <button class="tab-btn active"
                            data-tab="manage-videos"><?php echo $edit_video_resource ? 'Edit Video Resource' : 'Add New Video Resource'; ?></button>
                        <button class="tab-btn" data-tab="all-videos">All Video Resources</button>
                    </div>

                    <div id="manage-videos" class="tab-content active">
                        <div class="form-section">
                            <h3 class="form-title">
                                <?php echo $edit_video_resource ? 'Edit Video Resource' : 'Add New Video Resource'; ?>
                            </h3>
                            <form method="POST" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="action"
                                    value="<?php echo $edit_video_resource ? 'edit_video_resource' : 'add_video_resource'; ?>">
                                <?php if ($edit_video_resource): ?>
                                    <input type="hidden" name="id" value="<?php echo $edit_video_resource['id']; ?>">
                                    <?php if ($edit_video_resource['thumbnail_url']): ?>
                                        <input type="hidden" name="thumbnail_url_current"
                                            value="<?php echo htmlspecialchars($edit_video_resource['thumbnail_url']); ?>">
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="video_title" class="form-label">Title *</label>
                                        <input type="text" id="video_title" name="title" class="form-control" required
                                            autocomplete="off" placeholder="e.g., IELTS Writing Task 2 Tips"
                                            value="<?php echo $edit_video_resource ? htmlspecialchars($edit_video_resource['title']) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="video_platform" class="form-label">Platform</label>
                                        <div class="styled-select">
                                            <select id="video_platform" name="platform" class="form-control"
                                                autocomplete="off">
                                                <option value="youtube" <?php echo ($edit_video_resource && $edit_video_resource['platform'] == 'youtube') ? 'selected' : ''; ?>>
                                                    YouTube</option>
                                                <option value="vimeo" <?php echo ($edit_video_resource && $edit_video_resource['platform'] == 'vimeo') ? 'selected' : ''; ?>>Vimeo
                                                </option>
                                                <option value="custom" <?php echo ($edit_video_resource && $edit_video_resource['platform'] == 'custom') ? 'selected' : ''; ?>>Custom
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="video_description" class="form-label">Description</label>
                                    <textarea id="video_description" name="description" class="form-control form-textarea"
                                        autocomplete="off"><?php echo $edit_video_resource ? htmlspecialchars($edit_video_resource['description']) : ''; ?></textarea>
                                </div>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="video_url" class="form-label">Video URL *</label>
                                        <input type="text" id="video_url" name="video_url" class="form-control" required
                                            autocomplete="off" placeholder="https://youtube.com/embed/..."
                                            value="<?php echo $edit_video_resource ? htmlspecialchars($edit_video_resource['video_url']) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="thumbnail" class="form-label">Thumbnail Image</label>
                                        <?php if ($edit_video_resource && $edit_video_resource['thumbnail_url']): ?>
                                            <div class="file-preview-container">
                                                <div class="file-preview">
                                                    <img src="<?php echo htmlspecialchars($edit_video_resource['thumbnail_url']); ?>"
                                                        alt="Current Thumbnail">
                                                    <span>Current Thumbnail:
                                                        <?php echo basename($edit_video_resource['thumbnail_url']); ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="file-input-wrapper">
                                            <input type="file" id="thumbnail" name="thumbnail" class="form-control"
                                                accept="image/*">
                                            <label for="thumbnail" class="file-input-label">
                                                <i class="fas fa-upload"></i>
                                                <span><?php echo ($edit_video_resource && $edit_video_resource['thumbnail_url']) ? 'Change Thumbnail' : 'Upload Thumbnail'; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($edit_video_resource && $edit_video_resource['video_url']): ?>
                                    <div class="video-preview-container">
                                        <h4
                                            style="color: var(--royal-gold); margin-bottom: 10px; padding: 10px 20px; background: rgba(212, 175, 55, 0.1);">
                                            Video Preview:</h4>
                                        <?php if ($edit_video_resource['thumbnail_url']): ?>
                                            <div class="video-thumbnail-preview" style="position: relative; display: inline-block; width: 100%; max-width: 400px;">
                                                <img src="<?php echo htmlspecialchars($edit_video_resource['thumbnail_url']); ?>" 
                                                     alt="Video Thumbnail" 
                                                     class="img-fluid rounded"
                                                     style="width: 100%; height: auto; cursor: pointer;"
                                                     onclick="playVideoInModal('<?php echo htmlspecialchars($edit_video_resource['video_url']); ?>', '<?php echo $edit_video_resource['platform']; ?>')">
                                                <div class="play-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
                                                    <div class="play-button" style="width: 60px; height: 60px; background: var(--royal-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                                                        <i class="fas fa-play"></i>
                                                    </div>
                                                </div>
                                                <div class="duration-badge" style="position: absolute; bottom: 10px; right: 10px; background: rgba(0, 0, 0, 0.7); color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                                    <?php echo $edit_video_resource['duration'] ?: 'N/A'; ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="no-thumbnail-placeholder" style="width: 100%; max-width: 400px; height: 225px; background: #f8f9fa; border: 2px dashed #ddd; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                                <div class="text-center">
                                                    <i class="fas fa-image" style="font-size: 36px; margin-bottom: 10px;"></i>
                                                    <p>No Thumbnail Available</p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="duration" class="form-label">Duration</label>
                                        <input type="text" id="duration" name="duration" class="form-control"
                                            placeholder="5:30" autocomplete="off"
                                            value="<?php echo $edit_video_resource ? htmlspecialchars($edit_video_resource['duration']) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="category" class="form-label">Category</label>
                                        <input type="text" id="category" name="category" class="form-control"
                                            placeholder="IELTS Writing" autocomplete="off"
                                            value="<?php echo $edit_video_resource ? htmlspecialchars($edit_video_resource['category']) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="video_status" class="form-label">Status</label>
                                        <div class="styled-select">
                                            <select id="video_status" name="status" class="form-control" autocomplete="off">
                                                <option value="published" <?php echo ($edit_video_resource && $edit_video_resource['status'] == 'published') ? 'selected' : ''; ?>>
                                                    Published</option>
                                                <option value="draft" <?php echo ($edit_video_resource && $edit_video_resource['status'] == 'draft') ? 'selected' : ''; ?>>Draft
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tags" class="form-label">Tags (comma separated)</label>
                                    <input type="text" id="tags" name="tags" class="form-control"
                                        placeholder="IELTS, Writing, Task 2, Tips" autocomplete="off"
                                        value="<?php echo $edit_video_resource ? htmlspecialchars($edit_video_resource['tags']) : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Options</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_featured" id="is_featured_video"
                                            class="form-check-input" autocomplete="off" <?php echo ($edit_video_resource && $edit_video_resource['is_featured']) ? 'checked' : ''; ?>>
                                        <label for="is_featured_video" class="form-check-label">Featured Video</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-gold">
                                    <i class="fas fa-<?php echo $edit_video_resource ? 'save' : 'plus-circle'; ?>"></i>
                                    <?php echo $edit_video_resource ? 'Update Video Resource' : 'Add Video Resource'; ?>
                                </button>
                                <?php if ($edit_video_resource): ?>
                                    <a href="admin.php?section=video_resources" class="btn btn-dark"
                                        style="margin-left: 10px;"><i class="fas fa-times"></i> Cancel</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <div id="all-videos" class="tab-content">
                        <div class="data-section">
                            <div class="section-header">
                                <h3 class="section-title"><i class="fas fa-list"></i> All Video Resources</h3>
                                <div class="section-actions">
                                    <label for="videoSearch" style="display:none;">Search</label>
                                    <input type="text" class="form-control" placeholder="Search..." style="width: 200px;"
                                        id="videoSearch" autocomplete="off">
                                </div>
                            </div>
                            <div class="table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Platform</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $all_videos = mysqli_query($conn, "SELECT * FROM video_resources ORDER BY created_at DESC");
                                        while ($video = mysqli_fetch_assoc($all_videos)):
                                            ?>
                                            <tr>
                                                <td>#<?php echo $video['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($video['title']); ?></strong>
                                                    <?php if ($video['is_featured']): ?>
                                                        <span class="status-badge status-active"
                                                            style="font-size: 10px;">Featured</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span
                                                        class="status-badge <?php echo $video['platform'] === 'youtube' ? 'status-active' : ($video['platform'] === 'vimeo' ? 'status-pending' : 'status-inactive'); ?>">
                                                        <?php echo ucfirst($video['platform']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($video['duration']); ?></td>
                                                <td>
                                                    <button
                                                        class="status-toggle <?php echo $video['status'] === 'published' ? 'status-active' : 'status-inactive'; ?>"
                                                        onclick="toggleStatus('video_resource', <?php echo $video['id']; ?>, '<?php echo $video['status'] === 'published' ? 'draft' : 'published'; ?>')">
                                                        <i
                                                            class="fas fa-<?php echo $video['status'] === 'published' ? 'check-circle' : 'times-circle'; ?>"></i>
                                                        <?php echo ucfirst($video['status']); ?>
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="table-actions">
                                                        <a href="admin.php?section=video_resources&action=edit&type=video_resource&id=<?php echo $video['id']; ?>"
                                                            class="action-icon-btn" title="Edit"><i class="fas fa-edit"></i></a>
                                                        <button class="action-icon-btn"
                                                            onclick="confirmDelete('video_resource', <?php echo $video['id']; ?>, '<?php echo htmlspecialchars($video['title']); ?>')"
                                                            title="Delete"><i class="fas fa-trash"></i></button>
                                                        <?php if ($video['video_url']): ?>
                                                            <a href="<?php echo htmlspecialchars($video['video_url']); ?>"
                                                                target="_blank" class="action-icon-btn" title="Watch"><i
                                                                    class="fas fa-play"></i></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($current_section == 'blogs'): ?>
                <section id="blogs" class="content-section active">
                    <div class="welcome-banner">
                        <h2 class="welcome-title">Blog Posts Management</h2>
                        <p class="welcome-text">Manage your blog posts, create new content, and engage your audience.</p>
                    </div>

                    <div class="royal-tabs">
                        <button class="tab-btn active"
                            data-tab="manage-blogs"><?php echo $edit_blog ? 'Edit Blog Post' : 'Add New Blog Post'; ?></button>
                        <button class="tab-btn" data-tab="all-blogs">All Blog Posts</button>
                    </div>

                    <div id="manage-blogs" class="tab-content active">
                        <div class="form-section">
                            <form method="POST" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="action"
                                    value="<?php echo $edit_blog ? 'edit_blog' : 'add_blog'; ?>">
                                <?php if ($edit_blog): ?>
                                    <input type="hidden" name="id" value="<?php echo $edit_blog['id']; ?>">
                                    <?php if ($edit_blog['image']): ?>
                                        <input type="hidden" name="image_current"
                                            value="<?php echo htmlspecialchars($edit_blog['image']); ?>">
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="blog_title" class="form-label">Title *</label>
                                        <input type="text" id="blog_title" name="title" class="form-control" required
                                            autocomplete="off" placeholder="e.g., Tips for IELTS Writing Task 2"
                                            value="<?php echo $edit_blog ? htmlspecialchars($edit_blog['title']) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="blog_status" class="form-label">Status</label>
                                        <div class="styled-select">
                                            <select id="blog_status" name="status" class="form-control"
                                                autocomplete="off">
                                                <option value="draft" <?php echo ($edit_blog && $edit_blog['status'] == 'draft') ? 'selected' : ''; ?>>
                                                    Draft</option>
                                                <option value="published" <?php echo ($edit_blog && $edit_blog['status'] == 'published') ? 'selected' : ''; ?>>Published
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="blog_image" class="form-label">Featured Image</label>
                                    <input type="file" id="blog_image" name="image" class="form-control"
                                        accept="image/*" autocomplete="off">
                                    <?php if ($edit_blog && $edit_blog['image']): ?>
                                        <div class="mt-2">
                                            <p>Current Image:</p>
                                            <img src="<?php echo htmlspecialchars($edit_blog['image']); ?>" 
                                                 alt="Current Image" style="max-width: 200px; height: auto; border-radius: 8px;">
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="blog_content" class="form-label">Content *</label>
                                    <textarea id="blog_content" name="content" class="form-control" rows="15"
                                        placeholder="Enter your blog post content here..."><?php echo $edit_blog ? htmlspecialchars($edit_blog['content']) : ''; ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-gold">
                                    <i class="fas fa-<?php echo $edit_blog ? 'save' : 'plus-circle'; ?>"></i>
                                    <?php echo $edit_blog ? 'Update Blog Post' : 'Add Blog Post'; ?>
                                </button>
                                <?php if ($edit_blog): ?>
                                    <a href="admin.php?section=blogs" class="btn btn-dark"
                                        style="margin-left: 10px;"><i class="fas fa-times"></i> Cancel</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <div id="all-blogs" class="tab-content">
                        <div class="data-section">
                            <div class="section-header">
                                <h3 class="section-title"><i class="fas fa-list"></i> All Blog Posts</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="royal-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $blogs = getAllBlogsAdmin($conn);
                                        foreach ($blogs as $blog):
                                        ?>
                                            <tr>
                                                <td><?php echo $blog['id']; ?></td>
                                                <td>
                                                    <div class="table-item">
                                                        <div class="item-info">
                                                            <h4><?php echo htmlspecialchars($blog['title']); ?></h4>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="status-badge <?php echo $blog['status']; ?>">
                                                        <?php echo ucfirst($blog['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M j, Y', strtotime($blog['created_at'])); ?></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="admin.php?section=blogs&action=edit&type=blog&id=<?php echo $blog['id']; ?>"
                                                            class="action-btn edit-btn" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="admin.php?action=toggle_status&table=blogs&id=<?php echo $blog['id']; ?>&status=<?php echo $blog['status'] == 'published' ? 'draft' : 'published'; ?>&section=blogs"
                                                            class="action-btn <?php echo $blog['status'] == 'published' ? 'disable-btn' : 'enable-btn'; ?>" title="<?php echo $blog['status'] == 'published' ? 'Unpublish' : 'Publish'; ?>">
                                                            <i class="fas fa-<?php echo $blog['status'] == 'published' ? 'eye-slash' : 'eye'; ?>"></i>
                                                        </a>
                                                        <a href="admin.php?action=delete&table=blogs&id=<?php echo $blog['id']; ?>&section=blogs"
                                                            class="action-btn delete-btn" title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this blog post?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($current_section == 'settings'): ?>
                <section id="settings" class="content-section active">
                    <div class="welcome-banner">
                        <h2 class="welcome-title">System Settings</h2>
                        <p class="welcome-text">Configure your platform settings, appearance, and preferences.</p>
                    </div>

                    <div class="royal-tabs">
                        <button class="tab-btn active" data-tab="general-settings">General</button>
                        <button class="tab-btn" data-tab="appearance">Appearance</button>
                        <button class="tab-btn" data-tab="social-media">Social Media</button>
                        <button class="tab-btn" data-tab="contact-info">Contact Info</button>
                    </div>

                    <div id="general-settings" class="tab-content active">
                        <div class="form-section">
                            <h3 class="form-title">General Settings</h3>
                            <form method="POST" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update_settings">

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="site_name" class="form-label">Site Name *</label>
                                        <input type="text" id="site_name" name="site_name" class="form-control"
                                            autocomplete="off"
                                            value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                                    </div>
                                    
                                </div>

                                <div class="form-group">
                                    <label for="google_map_embed" class="form-label">Google Maps Embed</label>
                                    <textarea id="google_map_embed" name="google_map_embed"
                                        class="form-control form-textarea"
                                        rows="3"><?php echo htmlspecialchars($settings['google_map_embed'] ?? ''); ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Save General
                                    Settings</button>
                            </form>
                        </div>
                    </div>

                    <div id="appearance" class="tab-content">
                        <div class="form-section">
                            <h3 class="form-title">Appearance Settings</h3>
                            <form method="POST" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update_settings">

                                <div class="form-group">
                                    <label for="logo_file" class="form-label">Logo</label>
                                    <?php if (!empty($settings['logo_url'])): ?>
                                        <div class="file-preview-container">
                                            <div class="file-preview">
                                                <img src="<?php echo htmlspecialchars($settings['logo_url']); ?>"
                                                    alt="Current Logo">
                                                <span>Current Logo: <?php echo basename($settings['logo_url']); ?></span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="logo_url_current"
                                            value="<?php echo htmlspecialchars($settings['logo_url']); ?>">
                                    <?php endif; ?>
                                    <div class="file-input-wrapper">
                                        <input type="file" id="logo_file" name="logo_file" class="form-control"
                                            accept="image/*">
                                        <label for="logo_file" class="file-input-label">
                                            <i class="fas fa-upload"></i>
                                            <span><?php echo empty($settings['logo_url']) ? 'Upload Logo Image' : 'Change Logo Image'; ?></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="favicon_file" class="form-label">Favicon</label>
                                    <?php if (!empty($settings['favicon_url'])): ?>
                                        <div class="file-preview-container">
                                            <div class="file-preview">
                                                <img src="<?php echo htmlspecialchars($settings['favicon_url']); ?>"
                                                    alt="Current Favicon">
                                                <span>Current Favicon: <?php echo basename($settings['favicon_url']); ?></span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="favicon_url_current"
                                            value="<?php echo htmlspecialchars($settings['favicon_url']); ?>">
                                    <?php endif; ?>
                                    <div class="file-input-wrapper">
                                        <input type="file" id="favicon_file" name="favicon_file" class="form-control"
                                            accept="image/*">
                                        <label for="favicon_file" class="file-input-label">
                                            <i class="fas fa-upload"></i>
                                            <span><?php echo empty($settings['favicon_url']) ? 'Upload Favicon Image' : 'Change Favicon Image'; ?></span>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Save Appearance
                                    Settings</button>
                            </form>
                        </div>
                    </div>

                    <div id="social-media" class="tab-content">
                        <div class="form-section">
                            <h3 class="form-title">Social Media Settings</h3>
                            <form method="POST" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update_settings">

                                <!-- Social Media URLs -->
                                <div class="form-group">
                                    <label for="facebook_url" class="form-label">Facebook URL</label>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input type="url" id="facebook_url" name="facebook_url" class="form-control"
                                            placeholder="https://facebook.com/yourpage"
                                            value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>">
                                        <div class="form-check" style="margin-bottom: 0; white-space: nowrap;">
                                            <input type="checkbox" name="show_facebook" id="show_facebook"
                                                class="form-check-input" <?php echo $settings['show_facebook'] ? 'checked' : ''; ?>>
                                            <label for="show_facebook" class="form-check-label"
                                                style="color: var(--royal-text);">Show</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="instagram_url" class="form-label">Instagram URL</label>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input type="url" id="instagram_url" name="instagram_url" class="form-control"
                                            placeholder="https://instagram.com/yourprofile"
                                            value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>">
                                        <div class="form-check" style="margin-bottom: 0; white-space: nowrap;">
                                            <input type="checkbox" name="show_instagram" id="show_instagram"
                                                class="form-check-input" <?php echo $settings['show_instagram'] ? 'checked' : ''; ?>>
                                            <label for="show_instagram" class="form-check-label"
                                                style="color: var(--royal-text);">Show</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="twitter_url" class="form-label">Twitter URL</label>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input type="url" id="twitter_url" name="twitter_url" class="form-control"
                                            placeholder="https://twitter.com/yourhandle"
                                            value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>">
                                        <div class="form-check" style="margin-bottom: 0; white-space: nowrap;">
                                            <input type="checkbox" name="show_twitter" id="show_twitter"
                                                class="form-check-input" <?php echo $settings['show_twitter'] ? 'checked' : ''; ?>>
                                            <label for="show_twitter" class="form-check-label"
                                                style="color: var(--royal-text);">Show</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input type="url" id="linkedin_url" name="linkedin_url" class="form-control"
                                            placeholder="https://linkedin.com/company/yourcompany"
                                            value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? ''); ?>">
                                        <div class="form-check" style="margin-bottom: 0; white-space: nowrap;">
                                            <input type="checkbox" name="show_linkedin" id="show_linkedin"
                                                class="form-check-input" <?php echo $settings['show_linkedin'] ? 'checked' : ''; ?>>
                                            <label for="show_linkedin" class="form-check-label"
                                                style="color: var(--royal-text);">Show</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="youtube_url" class="form-label">YouTube URL</label>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input type="url" id="youtube_url" name="youtube_url" class="form-control"
                                            placeholder="https://youtube.com/c/yourchannel"
                                            value="<?php echo htmlspecialchars($settings['youtube_url'] ?? ''); ?>">
                                        <div class="form-check" style="margin-bottom: 0; white-space: nowrap;">
                                            <input type="checkbox" name="show_youtube" id="show_youtube"
                                                class="form-check-input" <?php echo $settings['show_youtube'] ? 'checked' : ''; ?>>
                                            <label for="show_youtube" class="form-check-label"
                                                style="color: var(--royal-text);">Show</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- In the general-settings tab, add this after the Google Maps Embed field: -->

                                <div class="form-group">
                                    <label for="android_app_link" class="form-label">Android App Link</label>
                                    <input type="url" id="android_app_link" name="android_app_link" class="form-control"
                                        autocomplete="off"
                                        value="<?php echo htmlspecialchars($settings['android_app_link'] ?? ''); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="ios_app_link" class="form-label">iOS App Link</label>
                                    <input type="url" id="ios_app_link" name="ios_app_link" class="form-control"
                                        autocomplete="off"
                                        value="<?php echo htmlspecialchars($settings['ios_app_link'] ?? ''); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="ios_org_pass" class="form-label">iOS Organization Pass (For backend use
                                        only, not displayed on site)</label>
                                    <input type="text" id="ios_org_pass" name="ios_org_pass" class="form-control"
                                        autocomplete="off"
                                        value="<?php echo htmlspecialchars($settings['ios_org_pass'] ?? ''); ?>">
                                </div>
                                <!-- Feature Toggles (Moved to Social Media Section) -->
                                <div class="form-group">
                                    <label class="form-label">Feature Toggles</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="enable_whatsapp_chat" id="enable_whatsapp_chat"
                                            class="form-check-input" <?php echo $settings['enable_whatsapp_chat'] ? 'checked' : ''; ?>>
                                        <label for="enable_whatsapp_chat" class="form-check-label">Enable WhatsApp
                                            Chat</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="enable_newsletter" id="enable_newsletter"
                                            class="form-check-input" <?php echo $settings['enable_newsletter'] ? 'checked' : ''; ?>>
                                        <label for="enable_newsletter" class="form-check-label">Enable Newsletter</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Save Social Media
                                    Settings</button>
                            </form>
                        </div>
                    </div>

                    <div id="contact-info" class="tab-content">
                        <div class="form-section">
                            <h3 class="form-title">Contact Information</h3>
                            <form method="POST" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update_settings">

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="primary_phone" class="form-label">Primary Phone *</label>
                                        <input type="tel" id="primary_phone" name="primary_phone" class="form-control"
                                            autocomplete="tel"
                                            value="<?php echo htmlspecialchars($settings['primary_phone']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="secondary_phone" class="form-label">Secondary Phone</label>
                                        <input type="tel" id="secondary_phone" name="secondary_phone" class="form-control"
                                            autocomplete="tel"
                                            value="<?php echo htmlspecialchars($settings['secondary_phone'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="row"
                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div class="form-group">
                                        <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                                        <input type="tel" id="whatsapp_number" name="whatsapp_number" class="form-control"
                                            autocomplete="tel"
                                            value="<?php echo htmlspecialchars($settings['whatsapp_number'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            autocomplete="email" value="<?php echo htmlspecialchars($settings['email']); ?>"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea id="address" name="address" class="form-control form-textarea"
                                        rows="3"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Save Contact
                                    Info</button>
                            </form>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const tabId = this.getAttribute('data-tab');
                    const tabGroup = this.closest('.royal-tabs');

                    if (tabGroup) {
                        tabGroup.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');

                        const contentParent = tabGroup.parentElement;
                        const allContents = contentParent.querySelectorAll('.tab-content');
                        allContents.forEach(c => c.classList.remove('active'));

                        const targetContent = document.getElementById(tabId);
                        if (targetContent) targetContent.classList.add('active');
                    }
                });
            });

            <?php if ($edit_subject): ?>
                const subTab = document.querySelector('[data-tab="manage-subjects"]');
                if (subTab) subTab.click();
            <?php endif; ?>
            <?php if ($edit_book): ?>
                const bookTab = document.querySelector('[data-tab="manage-books"]');
                if (bookTab) bookTab.click();
            <?php endif; ?>
            <?php if ($edit_speaking_example): ?>
                const speakingTab = document.querySelector('[data-tab="manage-speaking"]');
                if (speakingTab) speakingTab.click();
            <?php endif; ?>
            <?php if ($edit_video_resource): ?>
                const videoTab = document.querySelector('[data-tab="manage-videos"]');
                if (videoTab) videoTab.click();
            <?php endif; ?>
        });

        let deleteType = '';
        let deleteId = 0;
        function confirmDelete(type, id, name) {
            deleteType = type;
            deleteId = id;
            const modal = document.getElementById('deleteModal');
            document.getElementById('deleteMessage').textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
            modal.classList.add('active');
        }

        document.getElementById('confirmDelete').addEventListener('click', function () {
            window.location.href = `admin.php?action=delete&type=${deleteType}&id=${deleteId}&section=<?php echo $current_section; ?>`;
        });

        document.getElementById('cancelDelete').addEventListener('click', function () {
            document.getElementById('deleteModal').classList.remove('active');
        });

        function toggleStatus(type, id, newStatus) {
            if (confirm(`Change status to ${newStatus}?`)) {
                window.location.href = `admin.php?action=toggle_status&type=${type}&id=${id}&status=${newStatus}&section=<?php echo $current_section; ?>`;
            }
        }

        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');
        mobileToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        document.getElementById('fullscreenBtn').addEventListener('click', () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => console.log(err));
            } else {
                document.exitFullscreen();
            }
        });

        function updateClock() {
            const now = new Date();
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        setInterval(updateClock, 60000);
        updateClock();

        setTimeout(() => {
            const toast = document.querySelector('.royal-toast');
            if (toast) toast.remove();
        }, 5000);

        document.getElementById('subjectSearch')?.addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('#all-subjects tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        document.getElementById('bookSearch')?.addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('#all-books tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        document.getElementById('speakingSearch')?.addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('#all-speaking tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        document.getElementById('videoSearch')?.addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('#all-videos tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        const independentCheckbox = document.getElementById('is_independent');
        const subjectSelect = document.getElementById('book_subject_id');
        const freeMaterialCheckbox = document.getElementById('is_free');
        const bookPriceInput = document.getElementById('book_price');

        if (independentCheckbox && subjectSelect) {
            independentCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    subjectSelect.value = '';
                    subjectSelect.disabled = true;
                } else {
                    subjectSelect.disabled = false;
                }
            });

            if (independentCheckbox.checked) {
                subjectSelect.disabled = true;
            }
        }

        // Handle free material checkbox - disable price field when checked
        if (freeMaterialCheckbox && bookPriceInput) {
            freeMaterialCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    bookPriceInput.value = '0';
                    bookPriceInput.disabled = true;
                    bookPriceInput.style.opacity = '0.6';
                } else {
                    bookPriceInput.disabled = false;
                    bookPriceInput.style.opacity = '1';
                }
            });

            // Initialize state based on current checkbox state
            if (freeMaterialCheckbox.checked) {
                bookPriceInput.disabled = true;
                bookPriceInput.style.opacity = '0.6';
            }
        }

        const videoUrlInput = document.getElementById('video_url');
        const videoPreview = document.querySelector('.video-preview');

        if (videoUrlInput && videoPreview) {
            videoUrlInput.addEventListener('blur', function () {
                if (this.value.trim()) {
                    videoPreview.src = this.value;
                }
            });
        }

        const audioUrlInput = document.getElementById('audio_file');

        if (audioUrlInput) {
            audioUrlInput.addEventListener('blur', function () {
                if (this.value.trim()) {
                    // Refresh page to show audio preview
                }
            });
        }

        // File upload preview functionality
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function () {
                const label = this.nextElementSibling;

                if (this.files.length > 0) {
                    const file = this.files[0];
                    label.classList.add('has-file');
                    label.innerHTML = `<i class="fas fa-check-circle"></i><span>${file.name} (${formatBytes(file.size)})</span>`;

                    // Create image preview
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            // Find the preview container
                            let previewContainer = input.closest('.form-group').querySelector('.file-preview-container');
                            if (!previewContainer) {
                                previewContainer = document.createElement('div');
                                previewContainer.className = 'file-preview-container';
                                input.closest('.file-input-wrapper').before(previewContainer);

                                const previewDiv = document.createElement('div');
                                previewDiv.className = 'file-preview';
                                previewContainer.appendChild(previewDiv);

                                const img = document.createElement('img');
                                previewDiv.appendChild(img);

                                const span = document.createElement('span');
                                span.textContent = 'New Upload Preview';
                                previewDiv.appendChild(span);
                            }

                            const img = previewContainer.querySelector('img');
                            if (img) {
                                img.src = e.target.result;
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                } else {
                    label.classList.remove('has-file');
                    label.innerHTML = `<i class="fas fa-upload"></i><span>Choose File</span>`;
                }
            });
        });

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }
        
        // Function to play video in modal when clicking on thumbnail in admin
        function playVideoInModal(videoUrl, platform) {
            // Create a modal to play the video
            let videoEmbed;
            if (platform === 'youtube') {
                const ytId = extractYouTubeId(videoUrl);
                videoEmbed = `
                    <iframe src="https://www.youtube.com/embed/${ytId}?autoplay=1" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen style="width: 100%; height: 400px;"></iframe>
                `;
            } else if (platform === 'vimeo') {
                const vimeoId = extractVimeoId(videoUrl);
                videoEmbed = `
                    <iframe src="https://player.vimeo.com/video/${vimeoId}?autoplay=1" 
                            frameborder="0" 
                            allow="autoplay; fullscreen; picture-in-picture" 
                            allowfullscreen style="width: 100%; height: 400px;"></iframe>
                `;
            } else {
                // Determine the video type based on file extension for custom videos
                let videoType = 'video/mp4'; // default
                if (videoUrl.toLowerCase().includes('.webm')) {
                    videoType = 'video/webm';
                } else if (videoUrl.toLowerCase().includes('.ogg') || videoUrl.toLowerCase().includes('.ogv')) {
                    videoType = 'video/ogg';
                } else if (videoUrl.toLowerCase().includes('.mov')) {
                    videoType = 'video/quicktime';
                } else if (videoUrl.toLowerCase().includes('.avi')) {
                    videoType = 'video/x-msvideo';
                } else if (videoUrl.toLowerCase().includes('.wmv')) {
                    videoType = 'video/x-ms-wmv';
                } else if (videoUrl.toLowerCase().includes('.flv')) {
                    videoType = 'video/x-flv';
                } else if (videoUrl.toLowerCase().includes('.m4v')) {
                    videoType = 'video/mp4';
                } else if (videoUrl.toLowerCase().includes('.3gp')) {
                    videoType = 'video/3gpp';
                }
                
                videoEmbed = `
                    <video controls autoplay style="width: 100%; height: 400px;" preload="metadata">
                        <source src="${videoUrl}" type="${videoType}">
                        Your browser does not support the video tag.
                    </video>
                `;
            }
            
            // Create modal HTML
            const modalHtml = `
                <div id="adminVideoModal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Video Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ${videoEmbed}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Add modal to body if it doesn't exist
            if (!document.getElementById('adminVideoModal')) {
                document.body.insertAdjacentHTML('beforeend', modalHtml);
            }
            
            // Show the modal
            const modalElement = document.getElementById('adminVideoModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
        
        // Helper functions for video ID extraction (same as in resources.php)
        function extractYouTubeId(url) {
            const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
            const match = url.match(regExp);
            return (match && match[7].length === 11) ? match[7] : 'dQw4w9WgXcQ';
        }
        
        function extractVimeoId(url) {
            const regExp = /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
            const match = url.match(regExp);
            return match ? match[5] : '148751763';
        }
    </script>
</body>

</html>
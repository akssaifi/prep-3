<?php
require_once 'config.php';
$settings = getSettings($conn);

// Get course slug from URL
$course_slug = $_GET['slug'] ?? '';
$subject_slug = $_GET['subject'] ?? '';

include 'header.php';

if($subject_slug) {
    // Show single subject page with books
    include 'subject.php';
} elseif($course_slug) {
    // Show single course page with subjects
    include 'single_course.php';
} else {
    // Show all courses
    include 'all_courses.php';
}

include 'footer.php';
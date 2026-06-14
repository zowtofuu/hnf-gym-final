<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/mdl_subscriptions.php';

// Collect and sanitize filter inputs from GET parameters
$filters = [
    'search'          => trim($_GET['search'] ?? ''),
    'membership_type' => trim($_GET['membership_type'] ?? ''),
    'pass_type'       => trim($_GET['pass_type'] ?? ''),
    'expiring_filter' => trim($_GET['expiring_filter'] ?? ''),
    'status_filter'   => trim($_GET['status_filter'] ?? ''),
];

// Fetch dropdown options and filtered subscription records
$filterOptions = getSubscriptionFilterOptions($pdo);
$subscriptions  = getFilteredSubscriptions($pdo, $filters);

// Column keys map to subscription data fields; labels are used as table headers
$columns = [
    'client_name'      => 'Client Name',
    'membership_type'  => 'Membership Type',
    'membership_start' => 'Membership Start',
    'membership_end'   => 'Membership End',
    'pass_type'        => 'Pass Type',
    'subscription_start' => 'Pass Start',
    'subscription_end'   => 'Pass End',
    'display_status'   => 'Status',
];

require_once __DIR__ . '/../views/vw_subscriptions.php';
<?php

//If user is not logged in or if user is not an employee, redirect to home page
if (!is_user_logged_in()) {
    wp_redirect('http://localhost/login');
    exit;
} else {
    $user = wp_get_current_user();
    if (!in_array('employee', (array) $user->roles)) {
        wp_redirect('http://localhost/');
        exit;
    }
}

//If the tab variable is not set, then redirect to the companies tab
if (!isset($_GET['tab'])) {
    wp_redirect('http://localhost/my-companies/?tab=my-companies');
    exit;
}

//If the tab variable has an invalid value, redirect to the companies tab
if ($_GET['tab'] != "my-companies" && $_GET['tab'] != "sent-applications" && $_GET['tab'] != "send-application") {
    wp_redirect('http://localhost/my-companies/?tab=my-companies');
    exit;
}
?>

<?php BsWp::get_template_parts(array(
    'parts/shared/html-header',
    // 'parts/shared/header' 
)); ?>

<?php
//Setting some variables
$tab = $_GET['tab'];
$employeeId = get_current_user_id();
$employeeFieldId = 'user_' . $employeeId;
$companiesEmployed = 'employed_to_companies_list';
$applicationsSent = 'applications-sent';

//Leaving a company
if (isset($_POST['leftCompany'])) {
    $companyId = $_POST['leftCompany'];
    $companyFieldId = 'user_' . $companyId;
    $employeesList = 'company_employees_list';
    removeFromCustomField($companiesEmployed, $employeeFieldId, $companyId);
    removeFromCustomField($employeesList, $companyFieldId, $employeeId);
}

//Canceling an application
if (isset($_POST['cancelCompanyApplication'])) {
    $companyId = $_POST['cancelCompanyApplication'];
    $companyFieldId = 'user_' . $companyId;
    $applicationReceived = 'applications-received';
    removeFromCustomField($applicationsSent, $employeeFieldId, $companyId);
    removeFromCustomField($applicationReceived, $companyFieldId, $employeeId);
}

//Sending an application to a company
if (isset($_POST['appliedToCompany'])) {
    $companyId = $_POST['appliedToCompany'];
    $companyFieldId = 'user_' . $companyId;
    $applicationReceived = 'applications-received';
    addToCustomField($applicationsSent, $employeeFieldId, $companyId);
    addToCustomField($applicationReceived, $companyFieldId, $employeeId);
}

//Tab names
$firstTabName = "My companies";
$secondTabName = "Sent applications";
$thirdTabName = "Send an application";
if (get_field($companiesEmployed, $employeeFieldId) !== null) {
    $firstTabName = $firstTabName . " (" . count(get_field($companiesEmployed, $employeeFieldId)) . ")";
} else {
    $firstTabName = $firstTabName . "(0)";
}
if (get_field($applicationsSent, $employeeFieldId) !== null) {
    $secondTabName = $secondTabName . " (" . count(get_field($applicationsSent, $employeeFieldId)) . ")";
} else {
    $secondTabName = $secondTabName . "(0)";
}

//Tabs
if ($tab == "my-companies") {
    echo "<p>$firstTabName | <a href=\"http://localhost/my-companies/?tab=sent-applications\">$secondTabName</a> | <a href=\"http://localhost/my-companies/?tab=send-application\">$thirdTabName</a></p>";

    firstTab();
} else if ($tab == "sent-applications") {
    echo "<p><a href=\"http://localhost/my-companies/?tab=my-companies\">$firstTabName</a> | $secondTabName | <a href=\"http://localhost/my-companies/?tab=send-application\">$thirdTabName</a></p>";

    secondTab();
} else if ($tab == "send-application") {
    echo "<p><a href=\"http://localhost/my-companies/?tab=my-companies\">$firstTabName</a> | <a href=\"http://localhost/my-companies/?tab=sent-applications\">$secondTabName</a> | $thirdTabName</p>";

    thirdTab();
} else {
    wp_redirect('http://localhost/my-companies/?tab=my-companies');
    exit;
}

//Tab containing the list of joined companies
function firstTab()
{
    global $companiesEmployed;
    global $employeeFieldId;
    if (get_field($companiesEmployed, $employeeFieldId) !== null) {
        $listOfJoinedCompanies = get_field($companiesEmployed, $employeeFieldId);
        //Form to leave a company
        echo "<form method=\"post\">";
        wp_dropdown_users($args = [
            'name' => 'leftCompany',
            'include' => $listOfJoinedCompanies,
        ]);
        echo "<input type=\"submit\" value=\"Leave company\">";
        echo "</form>";
        //List of joined companies
        echo "<ol>";
        foreach ($listOfJoinedCompanies as $company) {
            $company = get_userdata($company);
            echo "<li>" . $company->user_login . "</li>";
        }
        echo "</ol>";
    } else {
        echo '<p>You don\'t belong to any company. Click <a href="http://localhost/my-companies/?tab=send-application">here</a> to send an application to a company.</p>';
    }
}
//Tab containing the list of sent applications
function secondTab()
{
    global $employeeFieldId;
    global $applicationsSent;
    if (get_field($applicationsSent, $employeeFieldId) !== null) {
        //Form to cancel an application
        echo "<form method=\"post\">";
        $listOfSentApplications = get_field($applicationsSent, $employeeFieldId);
        wp_dropdown_users($args = [
            'name' => 'cancelCompanyApplication',
            'include' => $listOfSentApplications,
        ]);
        echo "<input type=\"submit\" value=\"Cancel application\">";
        echo "</form>";
        //List of sent applications
        echo "<ol>";
        foreach ($listOfSentApplications as $sentApplication) {
            $application = get_userdata($sentApplication);
            echo "<li>";
            echo $application->user_login;
            echo "</li>";
        }
        echo "</ol>";
    } else {
        echo "<p>You haven't send any applications yet</p>";
    }
}
//Tab containing the form to send an application to a company
function thirdTab()
{
    global $applicationsSent;
    global $employeeFieldId;
    echo "<form method=\"post\">";
    if (get_field($applicationsSent, $employeeFieldId) !== null) {
        $excludedCompanies = get_field($applicationsSent, $employeeFieldId);
        wp_dropdown_users($args = [
            'role' => 'company',
            'name' => 'appliedToCompany',
            'exclude' => $excludedCompanies,
        ]);
    } else {
        wp_dropdown_users($args = [
            'role' => 'company',
            'name' => 'appliedToCompany',
        ]);
    }
    echo "<input type=\"submit\" value=\"Send Application\">";
    echo "</form>";
}
?>


<?php BsWp::get_template_parts(array(
    'parts/shared/footer',
    'parts/shared/html-footer'
)); ?>

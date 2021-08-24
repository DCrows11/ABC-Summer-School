<?php

//If user is not logged in or if user is not a company, redirect to home page
if (!is_user_logged_in()) {
    wp_redirect('http://localhost/login');
    exit;
} else {
    $user = wp_get_current_user();
    if (!in_array('company', (array) $user->roles)) {
        wp_redirect('http://localhost/');
        exit;
    }
}

//If the tab variable is not set, then redirect to the employees tab
if (!isset($_GET['tab'])) {
    wp_redirect('http://localhost/my-employees/?tab=my-employees');
    exit;
}

//If the tab variable doesn't have a valid value, redirect to the employees tab
if ($_GET['tab'] != "my-employees" && $_GET['tab'] != "received-applications") {
    wp_redirect('http://localhost/my-employees/?tab=my-employees');
    exit;
}
?>

<?php BsWp::get_template_parts(array(
    'parts/shared/html-header',
    'parts/shared/header' 
)); ?>

<div class="custom-content-class">

<?php
//Setting Variables
$tab = $_GET['tab'];
$companyId = get_current_user_id();
$companyFieldId = 'user_' . $companyId;
$companyEmployees = 'company_employees_list';
$applicationsFromEmployees = 'applications-received';

//Accepting an application
if (isset($_POST['acceptEmployeeApplication'])) {
    $employeeId = $_POST['acceptEmployeeApplication'];
    $employeeFieldId = 'user_' . $employeeId;
    $companiesEmployed = 'employed_to_companies_list';
    $applicationsSent = 'applications-sent';
    addToCustomField($companiesEmployed, $employeeFieldId, $companyId);
    addToCustomField($companyEmployees, $companyFieldId, $employeeId);
    removeFromCustomField($applicationsSent, $employeeFieldId, $companyId);
    removeFromCustomField($applicationsFromEmployees, $companyFieldId, $employeeId);
}
//Removing an employee
if (isset($_POST['removeEmployee'])) {
    $companiesEmployed = 'employed_to_companies_list';
    $employeeId = $_POST['removeEmployee'];
    $employeeFieldId = 'user_' . $employeeId;
    removeFromCustomField($companiesEmployed, $employeeFieldId, $companyId);
    removeFromCustomField($companyEmployees, $companyFieldId, $employeeId);
}

//Tab names
$firstTabName = "My employees";
$secondTabName = "Received applications";
if (get_field($companyEmployees, $companyFieldId) !== null) {
    $firstTabName = $firstTabName . " (" . count(get_field($companyEmployees, $companyFieldId)) . ")";
} else {
    $firstTabName = $firstTabName . " (0)";
}
if (get_field($applicationsFromEmployees, $companyFieldId) !== null) {
    $secondTabName = $secondTabName . " (" . count(get_field($applicationsFromEmployees, $companyFieldId)) . ")";
} else {
    $secondTabName = $secondTabName . " (0)";
}

//Tabs
if ($tab == "my-employees") {
    echo "<p>$firstTabName | <a href=\"http://localhost/my-employees/?tab=received-applications\">$secondTabName</a></p>";

    firstTab();
} else if ($tab == "received-applications") {
    echo "<p><a href=\"http://localhost/my-employees/?tab=my-employees\">$firstTabName</a> | $secondTabName</p>";

    secondTab();
} else {
    wp_redirect('http://localhost/my-employees/?tab=my-employees');
    exit;
}

//Tab containing the list of employees
function firstTab()
{
    global $companyEmployees;
    global $companyFieldId;
    if (get_field($companyEmployees, $companyFieldId) !== null) {
        //Form to remove employees
        echo "<form method=\"post\">";
        $employeeList = get_field($companyEmployees, $companyFieldId);
        wp_dropdown_users($args = [
            'name' => 'removeEmployee',
            'include' => $employeeList,
        ]);
        echo "<input type=\"submit\" value=\"Remove Employee\">";
        echo "</form>";
        //List of employees
        $employeeList = get_field($companyEmployees, $companyFieldId);
        echo "<ol>";
        foreach ($employeeList as $employee) {
            $employee = get_userdata($employee);
            echo "<li>";
            echo $employee->user_login;
            echo "</li>";
        }
        echo "</ol>";
    } else {
        echo '<p>Your comapny doesn\'t have any employees yet. Click <a href="http://localhost/my-employees/?tab=received-applications">here</a> to start accepting applications from employees.</p>';
    }
}
//Tab containing the list of applications from employees
function secondTab()
{
    global $companyFieldId;
    global $applicationsFromEmployees;
    if (get_field($applicationsFromEmployees, $companyFieldId) !== null) {
        //Form to accept an application
        echo "<form method=\"post\">";
        $includedEmployees = get_field($applicationsFromEmployees, $companyFieldId);
        wp_dropdown_users($args = [
            'name' => 'acceptEmployeeApplication',
            'include' => $includedEmployees,
        ]);
        echo "<input type=\"submit\" value=\"Accept application\">";
        echo "</form>";
        //List of invitations
        echo "<ol>";
        foreach (get_field($applicationsFromEmployees, $companyFieldId) as $invitedEmployeeId) {
            $invitedEmployee = get_userdata($invitedEmployeeId);
            echo "<li>";
            echo $invitedEmployee->user_login;
            echo "</li>";
        }
        echo "</ol>";
    } else {
        echo '<p>You haven\'t received any applications yet.</p>';
    }
}
?>

</div>

<?php BsWp::get_template_parts(array(
    'parts/shared/footer',
    'parts/shared/html-footer'
)); ?>

<?php

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Please see /external/bootsrap-utilities.php for info on BsWp::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Bootstrap 5.0.1
 * @autor 		Babobski
 */
?>

<?php

//If user is not logged in or if user is not a company, redirect to home page
if (!is_user_logged_in()) {
    wp_redirect('http://localhost/');
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
if ($_GET['tab'] != "my-employees" && $_GET['tab'] != "sent-invitations" && $_GET['tab'] != "add-employee") {
    wp_redirect('http://localhost/my-employees/?tab=my-employees');
    exit;
}
?>

<?php BsWp::get_template_parts(array(
    'parts/shared/html-header',
    // 'parts/shared/header' 
)); ?>

<?php
//Sending an invitation to an employee
if (isset($_POST['newEmployee'])) {
    $newEmployeeId = $_POST['newEmployee'];
    if (get_field('invitations_to_employees', 'user_' . get_current_user_id()) !== null) {
        if (!in_array($newEmployeeId, get_field('invitations_to_employees', 'user_' . get_current_user_id()))) {
            $listOfInvitations = get_field('invitations_to_employees', 'user_' . get_current_user_id());
            array_push($listOfInvitations, $newEmployeeId);
            update_field('invitations_to_employees', $listOfInvitations, 'user_' . get_current_user_id());
        }
    } else {
        $listOfInvitations = [
            $newEmployeeId,
        ];
        update_field('invitations_to_employees', $listOfInvitations, 'user_' . get_current_user_id());
    }
    $employeeFieldId = "user_" . $newEmployeeId;
    $companyId = get_current_user_id();
    if (get_field('invitations_from_companies', $employeeFieldId) !== null) {
        if (!in_array($companyId, get_field('invitations_from_companies', $employeeFieldId))) {
            $listOfInvitationsFromCompanies = get_field('invitations_from_companies', $employeeFieldId);
            array_push($listOfInvitationsFromCompanies, $companyId);
            update_field('invitations_from_companies', $listOfInvitationsFromCompanies, $employeeFieldId);
        }
    } else {
        $listOfInvitationsFromCompanies = [
            $companyId,
        ];
        update_field('invitations_from_companies', $listOfInvitationsFromCompanies, $employeeFieldId);
    }
}
//Removing an invitation
if (isset($_POST['cancelEmployeeInvitation'])) {
    $employeeToBeRemovedId = $_POST['cancelEmployeeInvitation'];

    //Remove the employee from the company's list of invitations
    if (count(get_field('invitations_to_employees', 'user_' . get_current_user_id())) > 1) {
        $listOfInvitations = get_field('invitations_to_employees', 'user_' . get_current_user_id());
        $listOfInvitations = \array_diff($listOfInvitations, [$employeeToBeRemovedId]);
        update_field('invitations_to_employees', $listOfInvitations, 'user_' . get_current_user_id());
    } else {
        update_field('invitations_to_employees', null, 'user_' . get_current_user_id());
    }

    //Remove the company from the employee's list of invitations
    $employeeFieldId = "user_" . $employeeToBeRemovedId;
    $companyId = get_current_user_id();
    if (count(get_field('invitations_from_companies', $employeeFieldId)) > 1) {
        $listOfInvitationsFromCompanies = get_field('invitations_from_companies', $employeeFieldId);
        $listOfInvitationsFromCompanies = \array_diff($listOfInvitationsFromCompanies, [$employeeToBeRemovedId]);
        update_field('invitations_from_companies', $listOfInvitationsFromCompanies, $employeeFieldId);
    }
    else {
        update_field('invitations_from_companies', null, $employeeFieldId);
    }
}
//Removing an employee
if (isset($_POST['removeEmployee'])) {
    $employeeToBeRemovedId = $_POST['removeEmployee'];

    //Remove the employee from the company's list of employees
    if (count(get_field('company_employees_list', 'user_' . get_current_user_id())) > 1) {
        $listOfEmployees = get_field('company_employees_list', 'user_' . get_current_user_id());
        $listOfEmployees = \array_diff($listOfEmployees, [$employeeToBeRemovedId]);
        update_field('company_employees_list', $listOfEmployees, 'user_' . get_current_user_id());
    } else {
        update_field('company_employees_list', null, 'user_' . get_current_user_id());
    }

    //Remove the company from the employee's list of companies
    $employeeFieldId = "user_" . $employeeToBeRemovedId;
    $companyId = get_current_user_id();
    if (count(get_field('employed_to_companies_list', $employeeFieldId)) > 1) {
        $listOfCompanies = get_field('employed_to_companies_list, $employeeFieldId');
        $listOfCompanies = \array_diff($listOfCompanies, [$employeeToBeRemovedId]);
        update_field('employed_to_companies_list', $listOfCompanies, $employeeFieldId);
    }
    else {
        update_field('employed_to_companies_list', null, $employeeFieldId);
    }
}

//Tab names
$tab = $_GET['tab'];
$companyFieldId = 'user_' . get_current_user_id();
$firstTabName = "My employees";
$secondTabName = "Invitations sent";
if (get_field('company_employees_list', 'user_' . get_current_user_id()) !== null) {
    $firstTabName = $firstTabName . " (" . count(get_field('company_employees_list', 'user_' . get_current_user_id())) . ")";
} else {
    $firstTabName = $firstTabName . " (0)";
}
if (get_field('invitations_to_employees', 'user_' . get_current_user_id()) !== null) {
    $secondTabName = $secondTabName . " (" . count(get_field('invitations_to_employees', 'user_' . get_current_user_id())) . ")";
} else {
    $secondTabName = $secondTabName . " (0)";
}
$thirdTabName = "Add employee";

//Tabs
if ($tab == "my-employees") {
    echo "<p>$firstTabName | <a href=\"http://localhost/my-employees/?tab=sent-invitations\">$secondTabName</a> | <a href=\"http://localhost/my-employees/?tab=add-employee\">$thirdTabName</a></p>";

    firstTab();
} else if ($tab == "sent-invitations") {
    echo "<p><a href=\"http://localhost/my-employees/?tab=my-employees\">$firstTabName</a> | $secondTabName | <a href=\"http://localhost/my-employees/?tab=add-employee\">$thirdTabName</a></p>";

    secondTab();
} else if ($tab == "add-employee") {
    echo "<p><a href=\"http://localhost/my-employees/?tab=my-employees\">$firstTabName</a> | <a href=\"http://localhost/my-employees/?tab=sent-invitations\">$secondTabName</a> | $thirdTabName</p>";

    thirdTab();
} else {
    wp_redirect('http://localhost/my-employees/?tab=my-employees');
    exit;
}

//Tab containing the list of approved employees
function firstTab()
{
    if (get_field('company_employees_list', 'user_' . get_current_user_id()) !== null) {
        //Form to remove employees
        echo "<form method=\"post\">";
        $includedEmployees = get_field('company_employees_list', 'user_' . get_current_user_id());
        wp_dropdown_users($args = [
            'name' => 'removeEmployee',
            'include' => $includedEmployees,
        ]);
        echo "<input type=\"submit\" value=\"Remove Employee\">";
        echo "</form>";
        //List of employees
        $employeeList = get_field('company_employees_list', 'user_' . get_current_user_id());
        echo "<ol>";
        foreach ($employeeList as $employee) {
            $employee = get_userdata($employee);
            echo "<li>";
            echo $employee->user_login;
            echo "</li>";
        }
        echo "</ol>";
    } else {
        echo '<p>Your comapny doesn\'t have any employees. Click <a href="http://localhost/my-employees/?tab=add-employee">here</a> to start adding employees.</p>';
    }
}
//Tab containing the list of employees to which invitations were sent
function secondTab()
{
    if (get_field('invitations_to_employees', 'user_' . get_current_user_id()) !== null) {
        //Form to cancel an invitation
        echo "<form method=\"post\">";
        $includedEmployees = get_field('invitations_to_employees', 'user_' . get_current_user_id());
        wp_dropdown_users($args = [
            'name' => 'cancelEmployeeInvitation',
            'include' => $includedEmployees,
        ]);
        echo "<input type=\"submit\" value=\"Cancel invitation\">";
        echo "</form>";

        //List of invitations
        echo "<ol>";
        foreach (get_field('invitations_to_employees', 'user_' . get_current_user_id()) as $invitedEmployeeId) {
            $invitedEmployee = get_userdata($invitedEmployeeId);
            echo "<li>";
            echo $invitedEmployee->user_login;
            echo "</li>";
        }
        echo "</ol>";
    }
}
//Tab containing the form to add new employees
function thirdTab()
{
    echo "<form method=\"post\">";
    if (get_field('invitations_to_employees', 'user_' . get_current_user_id()) !== null) {
        $excludedEmployees = get_field('invitations_to_employees', 'user_' . get_current_user_id());
        wp_dropdown_users($args = [
            'role' => 'employee',
            'name' => 'newEmployee',
            'exclude' => $excludedEmployees,
        ]);
    } else {
        wp_dropdown_users($args = [
            'role' => 'employee',
            'name' => 'newEmployee',
        ]);
    }
    echo "<input type=\"submit\" value=\"Add Employee\">";
    echo "</form>";
}
?>


<?php BsWp::get_template_parts(array(
    'parts/shared/footer',
    'parts/shared/html-footer'
)); ?>

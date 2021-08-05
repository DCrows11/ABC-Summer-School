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
if ($_GET['tab'] != "my-companies" && $_GET['tab'] != "received-invitations") {
    wp_redirect('http://localhost/my-companies/?tab=my-companies');
    exit;
}
?>

<?php BsWp::get_template_parts(array(
    'parts/shared/html-header',
    // 'parts/shared/header' 
)); ?>

<?php

//Accepting a company invitation
if (isset($_POST['companyJoined'])) {
    $employeeToBeChangedId = get_current_user_id();
    $companyToBeChangedId = $_POST['companyJoined'];
    $employeeToBeChangedIdForm = 'user_' . $employeeToBeChangedId;
    $companyToBeChangedIdForm = 'user_' . $companyToBeChangedId;

    //Remove the employee from the company's list of invitations
    if (count(get_field('invitations_to_employees', $companyToBeChangedIdForm)) > 1) {
        $listOfInvitations = get_field('invitations_to_employees', $companyToBeChangedIdForm);
        $listOfInvitations = \array_diff($listOfInvitations, [$employeeToBeChangedId]);
        update_field('invitations_to_employees', $listOfInvitations, $companyToBeChangedIdForm);
    } else {
        update_field('invitations_to_employees', null, $companyToBeChangedIdForm);
    }

    //Remove the company from the employee's list of invitations
    if (count(get_field('invitations_from_companies', $employeeToBeChangedIdForm)) > 1) {
        $listOfInvitationsFromCompanies = get_field('invitations_from_companies', $employeeToBeChangedIdForm);
        $listOfInvitationsFromCompanies = \array_diff($listOfInvitationsFromCompanies, [$employeeToBeChangedId]);
        update_field('invitations_from_companies', $listOfInvitationsFromCompanies, $employeeToBeChangedIdForm);
    }
    else {
        update_field('invitations_from_companies', null, $employeeToBeChangedIdForm);
    }

    //Add the employee to the company's list of employees
    if (get_field('company_employees_list', $companyToBeChangedIdForm) !== null) {
        if (!in_array($employeeToBeChangedId, get_field('company_employees_list', $companyToBeChangedIdForm))) {
            $listOfEmployees = get_field('company_employees_list', $companyToBeChangedIdForm);
            $listOfEmployees[] = $employeeToBeChangedId;
            update_field('company_employees_list', $listOfEmployees, $companyToBeChangedIdForm);
        }
    } else {
        $listOfEmployees = [
            $employeeToBeChangedId,
        ];
        update_field('company_employees_list', $listOfEmployees, $companyToBeChangedIdForm);
    }
    //Add the company to the employee's list of companies
    if (get_field('employed_to_companies_list', $employeeToBeChangedIdForm) !== null) {
        if (!in_array($companyToBeChangedId, get_field('employed_to_companies_list', $employeeToBeChangedIdForm))) {
            $listOfCompanies = get_field('employed_to_companies_list', $employeeToBeChangedIdForm);
            $listOfCompanies = $companyToBeChangedId;
            update_field('employed_to_companies_list', $listOfCompanies, $employeeToBeChangedIdForm);
        }
    } else {
        $listOfCompanies = [
            $companyToBeChangedId,
        ];
        update_field('employed_to_companies_list', $listOfCompanies, $employeeToBeChangedIdForm);
    }
}

//Tab names
$tab = $_GET['tab'];
$firstTabName = "My companies";
$secondTabName = "Received invitations";
if (get_field('employed_to_companies_list', 'user_' . get_current_user_id()) !== null) {
    $firstTabName = $firstTabName . " (" . count(get_field('employed_to_companies_list', 'user_' . get_current_user_id())) . ")";
}
else {
    $firstTabName = $firstTabName . "(0)";
}
if (get_field('invitations_from_companies', 'user_' . get_current_user_id()) !== null) {
    $secondTabName = $secondTabName . " (" . count(get_field('invitations_from_companies', 'user_' . get_current_user_id())) . ")";
}
else {
    $secondTabName = $secondTabName . "(0)";
}

//Tabs
if ($tab == "my-companies") {
    echo "<p>$firstTabName | <a href=\"http://localhost/my-companies/?tab=received-invitations\">$secondTabName</a></p>";

    firstTab();
} else if ($tab == "received-invitations") {
    echo "<p><a href=\"http://localhost/my-companies/?tab=my-companies\">$firstTabName</a> | $secondTabName</p>";

    secondTab();
} else {
    wp_redirect('http://localhost/my-companies/?tab=my-companies');
    exit;
}

//Tab containing the list of approved companies
function firstTab()
{
    if (get_field('employed_to_companies_list', 'user_' . get_current_user_id()) !== null) {
        $companiesList = get_field('employed_to_companies_list', 'user_' . get_current_user_id());
        echo "<ol>";
        foreach ($companiesList as $company) {
            $company = get_userdata($company);
            echo "<li>" . $company->user_login . "</li>";
        }
        echo "</ol>";
    } else {
        echo '<p>You don\'t belong to any company. Click <a href="http://localhost/my-companies/?tab=received-invitations">here</a> to see a list of invitations received from companies.</p>';
    }
}
//Tab containing the list of companies from which invitations were received
function secondTab()
{
    if (get_field('invitations_from_companies', 'user_' . get_current_user_id()) !== null) {
        //Form to join a company
        $includedCompanies = get_field('invitations_from_companies', 'user_' . get_current_user_id());
        echo "<form method=\"post\">";
        wp_dropdown_users($args = [
            'name' => 'companyJoined',
            'include' => $includedCompanies,
        ]);
        echo "<input type=\"submit\" value=\"Accept invitation\">";
        echo "</form>";
        //List of companies
        echo "<ol>";
        foreach (get_field('invitations_from_companies', 'user_' . get_current_user_id()) as $receivedInvitation) {
            $invitation = get_userdata($receivedInvitation);
            echo "<li>";
            echo $invitation->user_login;
            echo "</li>";
        }
        echo "</ol>";
    }
    else {
        echo "<p>You haven't received any invitations yet</p>";
    }
}
?>


<?php BsWp::get_template_parts(array(
    'parts/shared/footer',
    'parts/shared/html-footer'
)); ?>

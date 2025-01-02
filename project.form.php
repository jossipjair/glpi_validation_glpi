<?php

/**
 * ---------------------------------------------------------------------
 *
 * GLPI - Gestionnaire Libre de Parc Informatique
 *
 * http://glpi-project.org
 *
 * @copyright 2015-2023 Teclib' and contributors.
 * @copyright 2003-2014 by the INDEPNET Development Team.
 * @licence   https://www.gnu.org/licenses/gpl-3.0.html
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * ---------------------------------------------------------------------
 */

/**
 * @since 0.85
 */

use Glpi\Event;

include('../inc/includes.php');

if (empty($_GET["id"])) {
    $_GET["id"] = '';
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = '';
}

Session::checkLoginUser();

$project = new Project();
if (isset($_POST["add"])) {
    $project->check(-1, CREATE, $_POST);

    $newID = $project->add($_POST);
    Event::log(
        $newID,
        "project",
        4,
        "maintain",
        //TRANS: %1$s is the user login, %2$s is the name of the item
        sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
    );
    if ($_SESSION['glpibackcreated']) {
        Html::redirect($project->getLinkURL());
    } else {
        Html::back();
    }
} else if (isset($_POST["delete"])) {
    $project->check($_POST["id"], DELETE);

    $project->delete($_POST);
    Event::log(
        $_POST["id"],
        "project",
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $project->redirectToList();
} else if (isset($_POST["restore"])) {
    $project->check($_POST["id"], DELETE);

    $project->restore($_POST);
    Event::log(
        $_POST["id"],
        "project",
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $project->redirectToList();
} else if (isset($_POST["purge"])) {
    $project->check($_POST["id"], PURGE);
    $project->delete($_POST, 1);

    Event::log(
        $_POST["id"],
        "project",
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $project->redirectToList();
} else if (isset($_POST["update"])) {
    $project->check($_POST["id"], UPDATE);

    $project->update($_POST);
    Event::log(
        $_POST["id"],
        "project",
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );

    Html::back();
} else if (isset($_GET['_in_modal'])) {
    Html::popHeader(Budget::getTypeName(1), $_SERVER['PHP_SELF'], true);
    $project->showForm($_GET["id"], ['withtemplate' => $_GET["withtemplate"]]);
    Html::popFooter();
} else {
    if (isset($_GET['showglobalkanban']) && $_GET['showglobalkanban']) {
        Html::header(Project::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "project");
        $project->showKanban(0);
        Html::footer();
    } else {
        $menus = ["tools", "project"];
        Project::displayFullPageForItem($_GET["id"], $menus, [
            'withtemplate' => $_GET["withtemplate"],
            'formoptions'  => "data-track-changes=true"
        ]);
    }
}
?>

<script>
    console.info('Adición de Validación Campo TiempoReal');
    console.info('DOMContentLoaded usado para carga dinámica de DOM');
    console.info('Búsqueda de boton "add" en la posicion [2] debido a que hay varios botones llamados "add"');
    console.info('Búsqueda de formulario "itil-form" donde se encuentra el campo "tiemporealhrsfield" que se genera desde plugin');
    console.info('https://github.com/jossipjair/glpi_validation_ticket');

    console.log('Prueba Proyecto');
    document.addEventListener('DOMContentLoaded', function() {
        function validateStatusFormField() {
            const asset_form = document.querySelector('form[name="asset_form"]');
         
            if (asset_form) {
                const inputField = asset_form.querySelector('select[name="projectstates_id"]');
                if(inputField ){
                    return inputField.value > 0;
                }else{
                    return false;
                }
            }
            return false;
        }

        function validateGroupsFormField() {
            const asset_form = document.querySelector('form[name="asset_form"]');
         
            if (asset_form) {
                const inputField = asset_form.querySelector('select[name="groups_id"]');
                if(inputField ){
                    return inputField.value > 0;
                }else{
                    return false;
                }
            }
            return false;
        }

        function validateUsersFormField() {
            const asset_form = document.querySelector('form[name="asset_form"]');
         
            if (asset_form) {
                const inputField = asset_form.querySelector('select[name="users_id"]');
                if(inputField ){
                    return inputField.value > 0;
                }else{
                    return false;
                }
            }
            return false;
        }

        function validatePlanStartDateFormField() {
            const asset_form = document.querySelector('form[name="asset_form"]');
            if (asset_form) {
                const inputField = asset_form.querySelector('input[name="plan_start_date"]');
                if (inputField) {
                    return inputField.value.trim() !== '';
                } else {
                    return false;
                }
            }
            return false;
        }

        function validateNameFormField() {
            const asset_form = document.querySelector('form[name="asset_form"]');
            if (asset_form) {
                const inputField = asset_form.querySelector('input[name="name"]');
                if (inputField) {
                    return inputField.value.trim() !== '';
                } else {
                    return false;
                }
            }
            return false;
        }

        function validatePlanEndDateFormField() {
            const asset_form = document.querySelector('form[name="asset_form"]');
            if (asset_form) {
                const inputField = asset_form.querySelector('input[name="plan_end_date"]');
                if (inputField) {
                    return inputField.value.trim() !== '';
                } else {
                    return false;
                }
            }
            return false;
        }

        function setupValidation(){

            const boton = document.querySelector('button[type="submit"][name="add"]');
          
            if (!boton.hasAttribute('data-listener-added')) {
                boton.addEventListener('click', function(event){

                let isValid = true;

                if(!validateNameFormField()){
                    alert('⚠️️ El campo "Nombre" no puede estar vacío. ⚠️ ');
                    isValid = false;
                }

                if(!validateStatusFormField()){
                    alert('⚠️️ El campo "Estado" no puede estar vacío. ⚠️ ');
                    isValid = false;
                }

                if(!validateGroupsFormField()){
                    alert('⚠️️ El campo "Grupo" no puede estar vacío. ⚠️ ');
                    isValid = false;
                }

                if(!validateUsersFormField()){
                    alert('⚠️️ El campo "Usuario" no puede estar vacío. ⚠️ ');
                    isValid = false;
                }

                if(!validatePlanStartDateFormField()){
                    alert('⚠️️ El campo "Fecha Inicio Planificada" no puede estar vacío. ⚠️ ');
                    isValid = false;
                }

                if(!validatePlanEndDateFormField()){
                    alert('⚠️️ El campo "Fecha Fin Planificada" no puede estar vacío. ⚠️ ');
                    isValid = false;
                }

    
                console.log('Valida Estado', validateStatusFormField());
                console.log('Valida Grupo', validateGroupsFormField());
                console.log('Valida Usuario', validateUsersFormField());
                console.log('Valida Fecha Inicial', validatePlanStartDateFormField());
                console.log('Valida Fecha Final', validatePlanEndDateFormField());
        
                event.preventDefault();
               
            });
                boton.setAttribute('data-listener-added', 'true');
            }
       

        }

    


        // Configura el observer para observar cambios en el cuerpo del documento
        const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            // Verifica si ambos formularios están presentes en el DOM

            const assetForm = document.querySelector('form[name="asset_form"]');

            if (assetForm) {
                // Configura la validación si ambos formularios están presentes
                setupValidation();
                
                // Deja de observar si ya no es necesario
                observer.disconnect();
            }
        });
    });
    // Configura el observer para observar el cuerpo del documento
    observer.observe(document.body, { childList: true, subtree: true });
    });
</script>
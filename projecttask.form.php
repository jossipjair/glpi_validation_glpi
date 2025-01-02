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

Session::checkCentralAccess();

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["projects_id"])) {
    $_GET["projects_id"] = "";
}
if (!isset($_GET["projecttasks_id"])) {
    $_GET["projecttasks_id"] = "";
}
$task = new ProjectTask();

if (isset($_POST["add"])) {
    $task->check(-1, CREATE, $_POST);
    $task->add($_POST);

    Event::log(
        $task->fields['projects_id'],
        'project',
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s adds a task'), $_SESSION["glpiname"])
    );
    if ($_SESSION['glpibackcreated']) {
        Html::redirect($task->getLinkURL());
    } else {
        Html::redirect(ProjectTask::getFormURL() . "?projects_id=" . $task->fields['projects_id']);
    }
} else if (isset($_POST["purge"])) {
    $task->check($_POST['id'], PURGE);
    $task->delete($_POST, 1);

    Event::log(
        $task->fields['projects_id'],
        'project',
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s purges a task'), $_SESSION["glpiname"])
    );
    Html::redirect(Project::getFormURLWithID($task->fields['projects_id']));
} else if (isset($_POST["update"])) {
    $task->check($_POST["id"], UPDATE);
    $task->update($_POST);

    Event::log(
        $task->fields['projects_id'],
        'project',
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s updates a task'), $_SESSION["glpiname"])
    );
    Html::back();
} else if (isset($_GET['_in_modal'])) {
    Html::popHeader(ProjectTask::getTypeName(1), $_SERVER['PHP_SELF'], true);
    $task->showForm($_GET["id"], ['withtemplate' => $_GET["withtemplate"]]);
    Html::popFooter();
} else {
    $menus = ["tools", "project"];
    ProjectTask::displayFullPageForItem($_GET['id'], $menus, $_GET);
}

?>

<script>
    console.info('Adición de Validación Campos en Tareas de Proyecto');
    console.info('DOMContentLoaded usado para carga dinámica de DOM');
    console.info('Búsqueda de formulario "asset_form" donde se encuentran los campos del form dinámico');
    console.info('https://github.com/jossipjair/glpi_validation_glpi');

    document.addEventListener('DOMContentLoaded', function() {

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

        function validateDescriptionFormField() {
            const asset_form = document.querySelector('form[name="asset_form"]');
            if (asset_form) {
                const inputField = document.getElementById('tinymce');

                console.log('Campo Descripción', inputField.value.trim());

                if (inputField) {
                    return inputField.value.trim() !== '';
                } else {
                    return false;
                }
            }
            return false;
        }


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
          
            if(boton != null){
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

                if(!validatePlanStartDateFormField()){
                    alert('⚠️️ El campo "Fecha Inicio Planificada" no puede estar vacío. ⚠️ ');
                    isValid = false;
                }

                if(!validatePlanEndDateFormField()){
                    alert('⚠️️ El campo "Fecha Fin Planificada" no puede estar vacío. ⚠️ ');
                    isValid = false;
                }

                
                /*if(!validateDescriptionFormField()){
                    alert('⚠️️ El campo "Descripción" no puede estar vacío. ⚠️ ');
                    isValid = false;
                }*/

              
            // Si hay errores, prevenir el envío del formulario y mostrar el mensaje
            if (!isValid) {

                event.preventDefault();
            }
                //console.log('Valida Estado', validateStatusFormField());
                //console.log('Valida Grupo', validateGroupsFormField());
                //console.log('Valida Usuario', validateUsersFormField());
                //console.log('Valida Fecha Inicial', validatePlanStartDateFormField());
                //console.log('Valida Fecha Final', validatePlanEndDateFormField());
        
         
               
            });
                boton.setAttribute('data-listener-added', 'true');
            }
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

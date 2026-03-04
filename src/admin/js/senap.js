$(document).ready(function(){

    checkSession({
        success: {
            function: setMenu,
            attr: {
                template_file: 'templates/other/menu.php',
                element_id_section: 'menu-list',
                element_attr: {
                    index: 1
                }
            }
        },
        failed: {
            function: redirectTo,
            attr: '../../index.html'
        },
        location: '../../service/check_session.php'
    });
});

function searchSenap(file_type){

    attr = null;

    csv_names = {
        1: 'SENAP_Carga_NoticiaCriminal',
        2: 'SENAP_Carga_CarpetaInvestigacion',
        3: 'SENAP_Carga_EtapaInvestigacion',
        4: 'SENAP_Carga_ActosInvestigacion',
        5: 'SENAP_Carga_Delitos',
        6: 'SENAP_Carga_Aseguramientos',
        7: 'SENAP_Carga_VictimasDelito',
        8: 'SENAP_Carga_ImputadoDelito',
        9: 'SENAP_Carga_VictimaImputado',
        10: 'SENAP_Carga_Determinacion',
        11: 'SENAP_Carga_Proceso',
        12: 'SENAP_Carga_MandamientosJudiciales',
        13: 'SENAP_Carga_InvestigacionComplementaria',
        14: 'SENAP_Carga_MedidasCautelares',
        15: 'SENAP_Carga_EtapaIntermedia',
        16: 'SENAP_Carga_MASC',
        17: 'SENAP_Carga_Sobreseimiento',
        18: 'SENAP_Carga_SuspensionCondicional',
        19: 'SENAP_Carga_Sentencia'
    }

    date = document.getElementById('main-search-search-month').value;
    procedure_op = document.getElementById('main-search-option').value;

    date_format = null;
    completed_form = false;


    if(date == "" || procedure_op == ""){
        completed_form = false;
    }
    else{
        date_format = new Date(date+'-01');
        date_format.setHours(date_format.getHours()+6); 
        completed_form = true;
    }

    if(attr == null && completed_form){
        attr = {
            url_service_file: 'service/get_senap_procedure_data.php',
            parameters: {
                month: date_format.getMonth()+1,
                year: date_format.getFullYear(),
                procedure_op: procedure_op,
                procedure: 1
            },
            on_success: {
                functions: [
                    /*{
                        function: drawTable,
                        attr: {
                            data: null,
                            url_service_file: 'templates/tables/default_table.php',
                            element_id: 'records-section'
                        },
                        response: true
                    },*/
                    {
                        function: createExcelReportCopy,
                        attr: {
                            data: null,
                            url_service_file: file_type == 'csv'? 'templates/excel/report_test_csv.php' : 'templates/excel/report_test.php',
                            file_name: file_type == 'csv'? csv_names[procedure_op]+'.csv' : csv_names[procedure_op]+'.xlsx'
                        },
                        response: true
                    }
                ]
            }
        }

        if(attr.url_service_file){
            console.log('showl');
            showLoading(true);
    
            $.ajax({
                url: attr.url_service_file,
                type: 'POST',
                dataType: 'JSON',
                data: attr.parameters,
                cache: false
            }).done(function(response){
                console.log(response);
                //test = response;
    
                for(os_function in attr.on_success.functions){
                    if(attr.on_success.functions[os_function].response){
                        attr.on_success.functions[os_function].attr.data = response;
                    }
                    attr.on_success.functions[os_function].function(attr.on_success.functions[os_function].attr);
                }
    
            });
        }
    }
    else{
        Swal.fire('Campos faltantes', 'Tiene que completar alguno de los campos para completar la busqueda', 'warning');
    }   
}

function changeSenapForm(section){

    console.log('changing section form');
    
    switch(section){

        case 'dump':
            changeMenu({
                class: 'senap-menu-button',
                current_section: section
            });
            loadForm({
                section: section,
                form_file_location: 'forms/senap_dump_form.php',
                content_div_id: 'senap-form-section'
            });
            break;
        case 'reports':
            changeMenu({
                class: 'senap-menu-button',
                current_section: section
            });
            loadForm({
                section: section,
                form_file_location: 'forms/senap_reports_form.php',
                content_div_id: 'senap-form-section'
            });
            break;
    }
}

function checkSenapBeforeDump(){

    showLoading(true);
    
    console.log('checking senap ...');

    let senap_dump_op = document.getElementById('senap-dump-option').value;

    let current_senap_attr = getSenapAttr({
        index: senap_dump_op
    });

    let month_year = null;

    let start_dump = false;

    let parameters = {};

    if(document.getElementById('senap-dump-search-month')){

        console.log('checo si existe el search month');

        if(document.getElementById('senap-dump-search-month').value == 0){

            console.log('checo si hay algun valor');

            Swal.fire('Campos faltantes', 'Tiene que completar alguno de los campos para completar la busqueda', 'warning');
        }
        else{

            console.log('si hay valor');

            month_year = convertDateFormat({
                date: document.getElementById('senap-dump-search-month').value,
                convert_to: 1
            });

            parameters = {
                month: month_year.month,
                year: month_year.year
            }

            start_dump = true;

            console.log('converti el valor de la fecha: ',month_year);
        }
    }

    console.log('voy a mandar: ',parameters);

    console.log('file location? ', current_senap_attr);

    console.log('start_dump? ', start_dump);


    if(start_dump){

        console.log('entre a buscar ');

        $.ajax({
            url: current_senap_attr.search_data_file,
            type: 'POST',
            dataType: 'JSON',
            data: parameters,
            cache: false
        }).done(function(response){
    
            console.log(response);
    
            if(response.state == 'success'){

                showLoading(false);
                Swal.fire('Datos previamente volcados', 'Este apartado ya contiene datos en su tabla respectiva', 'warning');
            }
            else if(response.state == 'not_found'){
    
                if(senap_dump_op >= 12){
                    checkExistantSenapSection({
                        parameters: parameters,
                        index_section: 11
                    });
                }
                else{
                    dumpSenap();
                }
            }
            else{

                showLoading(false);
                Swal.fire('Error', 'Ha ocurrido un error, vuelva a intentarlo', 'error');
            }
        });
    }
    else{
        console.log('no ?????');
    }
}

function dumpSenap(){

    console.log('Dumping senap ...');

    let current_senap_attr = {};

    let month_year = null;

    let senap_dump_op = document.getElementById('senap-dump-option').value;

    if(document.getElementById('senap-dump-search-month')){

        console.log('checo si existe el search month');

        if(document.getElementById('senap-dump-search-month').value == ''){

            console.log('checo si hay algun valor');

            Swal.fire('Campos faltantes', 'Tiene que completar alguno de los campos para completar la busqueda', 'warning');
        }
        else{

            console.log('si hay valor');

            current_senap_attr = getSenapAttr({
                index: senap_dump_op
            });

            month_year = convertDateFormat({
                date: document.getElementById('senap-dump-search-month').value,
                convert_to: 1
            });

            console.log('converti el valor de la fecha: ',month_year);
        }
    }

    let parameters = {
        month: month_year.month,
        year: month_year.year
    }

    console.log('voy a mandar: ',parameters);

    console.log('file location? ', current_senap_attr);

    if(current_senap_attr != {}){
        $.ajax({
            url: current_senap_attr.dump_file,
            type: 'POST',
            dataType: 'JSON',
            data: parameters,
            cache: false
        }).done(function(response){
    
            console.log(response);
    
            showLoading(false);
    
            Swal.fire('¡Exito!', 'Datos volcados correctamente', 'success');
        });
    }
    else{
        showLoading(false);
        Swal.fire('Error', 'Ha ocurrido un error, vuelva a intentarlo', 'error');
    }
}

function checkExistantSenapSection(attr){
    
    console.log('checking senap especific section ...');

    let current_senap_attr = getSenapAttr({
        index: attr.index_section
    });

    console.log('voy a mandar: ',attr.parameters);

    console.log('file location? ', current_senap_attr);

    $.ajax({
        url: current_senap_attr.search_data_file,
        type: 'POST',
        dataType: 'JSON',
        data: attr.parameters,
        cache: false
    }).done(function(response){

        console.log(response);

        if(response.state == 'success'){
            dumpSenap();
        }
        else if(response.state == 'not_found'){

            showLoading(false);
            Swal.fire('Datos de la tabla procesos faltantes', 'Se necesita tener los datos volcados de procesos para poder volcar esta seccion', 'warning');
        }
        else{

            showLoading(false);
            Swal.fire('Error', 'Ha ocurrido un error, vuelva a intentarlo', 'error');
        }
    });
}

function getSenapAttr(attr){

    console.log('entre a get senap attr');

    let senap_json = senapJSON();
    let dump_file = null;

    let senap_attr = {};

    for(element in senap_json){

        console.log('recorriendo ciclo ...', senap_json[element].index);

        if(senap_json[element].index == attr.index){

            senap_attr = senap_json[element];
            break;
        }
    }

    console.log('se supone q acabe el ciclo');

    if(senap_attr != {}){

        return senap_attr;
    }
    else{

        return senap_attr;
    }
}

function senapJSON(){

    let senap = {
        SENAP_Carga_NoticiaCriminal: {
            index: 1,
            dump_file: null,
            search_data_file: null
        },
        SENAP_Carga_CarpetaInvestigacion: {
            index: 2,
            dump_file: null,
            search_data_file: null
        },
        SENAP_Carga_EtapaInvestigacion: {
            index: 3,
            dump_file: 'service/senap/dump_3_etapa_investigacion.php',
            search_data_file: 'service/senap/search_3_etapa_investigacion.php'
        },
        SENAP_Carga_ActosInvestigacion: {
            index: 4,
            dump_file: 'service/senap/dump_4_actos_investigacion.php',
            search_data_file: 'service/senap/search_4_actos_investigacion.php'
        },
        SENAP_Carga_Delitos: {
            index: 5,
            dump_file: null,
            search_data_file: null
        },
        SENAP_Carga_Aseguramientos: {
            index: 6,
            dump_file: 'service/senap/dump_6_aseguramientos.php',
            search_data_file: 'service/senap/search_6_aseguramientos.php'
        },
        SENAP_Carga_VictimasDelito: {
            index: 7,
            dump_file: null,
            search_data_file: null
        },
        SENAP_Carga_ImputadoDelito: {
            index: 8,
            dump_file: null,
            search_data_file: null
        },
        SENAP_Carga_VictimaImputado: {
            index: 9,
            dump_file: null,
            search_data_file: null
        },
        SENAP_Carga_Determinacion: {
            index: 10,
            dump_file: 'service/senap/dump_10_determinaciones.php',
            search_data_file: 'service/senap/search_10_determinaciones.php'
        },
        SENAP_Carga_Proceso: {
            index: 11,
            dump_file: 'service/senap/dump_11_procesos.php',
            search_data_file: 'service/senap/search_11_procesos.php'
        },
        SENAP_Carga_MandamientosJudiciales: {
            index: 12,
            dump_file: 'service/senap/dump_12_mandamientos_judiciales.php',
            search_data_file: 'service/senap/search_12_mandamientos_judiciales.php'
        },
        SENAP_Carga_InvestigacionComplementaria: {
            index: 13,
            dump_file: 'service/senap/dump_13_investigacion_complementaria.php',
            search_data_file: 'service/senap/search_13_investigacion_complementaria.php'
        },
        SENAP_Carga_MedidasCautelares: {
            index: 14,
            dump_file: 'service/senap/dump_14_medidas_cautelares.php',
            search_data_file: 'service/senap/search_14_medidas_cautelares.php'
        },
        SENAP_Carga_EtapaIntermedia: {
            index: 15,
            dump_file: 'service/senap/dump_15_etapa_intermedia.php',
            search_data_file: 'service/senap/search_15_etapa_intermedia.php'
        },
        SENAP_Carga_MASC: {
            index: 16,
            dump_file: 'service/senap/dump_16_masc.php',
            search_data_file: 'service/senap/search_16_masc.php'
        },
        SENAP_Carga_Sobreseimiento: {
            index: 17,
            dump_file: 'service/senap/dump_17_sobreseimientos.php',
            search_data_file: 'service/senap/search_17_sobreseimientos.php'
        },
        SENAP_Carga_SuspensionCondicional: {
            index: 18,
            dump_file: 'service/senap/dump_18_suspension_condicional.php',
            search_data_file: 'service/senap/search_18_suspension_condicional.php'
        },
        SENAP_Carga_Sentencia: {
            index: 19,
            dump_file: 'service/senap/dump_19_sentencias.php',
            search_data_file: 'service/senap/search_19_sentencias.php'
        }
    }

    return senap;
}
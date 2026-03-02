$(document).ready(function(){

    checkSession({
        success: {
            function: setMenu,
            attr: {
                template_file: 'templates/other/menu.php',
                element_id_section: 'menu-list',
                element_attr: {
                    index: 8
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
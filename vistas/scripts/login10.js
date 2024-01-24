$("#frmAcceso").on('submit', function (e) {
    e.preventDefault();
    login = $("#login").val();
    clave = $("#clave").val();

    console.log("hace la validación =)");

    $.post("../ajax/usuario.php?op=verificar", { "logina": login, "clavea": clave },
        function (data) {
            console.log(data);
            if (data == 0) {
                $("#btnGuardar").prop("disabled", false);
                bootbox.alert("Su usuario está desactivado, comuníquese con el administrador.");
            } else if (data == 1) {
                bootbox.alert("El usuario no se encuentra disponible, comuníquese con el administrador.");
            } else if (data == 2) {
                bootbox.alert("El local en donde usted está trabajando está desactivado, comuníquese con el administrador.");
            } else if (data != "null") {
                $(location).attr("href", "escritorio.php");
            } else {
                bootbox.alert("Usuario y/o Contraseña incorrectos");
            }
        });
})

function mostrarClave() {
    console.log("di click =)");
    var claveInput = $('#clave');
    var ojitoIcon = $('#mostrarClave i');

    if (claveInput.attr('type') === 'password') {
        claveInput.attr('type', 'text');
        ojitoIcon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        claveInput.attr('type', 'password');
        ojitoIcon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
}
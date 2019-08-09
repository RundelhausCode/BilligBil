function pws() {
    var x = document.getElementById("pass");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

function re() {
    var str = window.location.href;
    if (str.indexOf('?') == -1) {
        window.location.href = "?home";
   }
}




$(document).ready(function() {
    var table = $('#data').DataTable();

    $('#data tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
        window.location.href = "?card="+data[0];
    } );
} );


$(document).ready(function() {
    var table = $('#example').DataTable();

    $('#example tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
        alert( 'You clicked on '+data[0]+'\'s row' );
    } );
} );
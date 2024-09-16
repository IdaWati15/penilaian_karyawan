$(document).ready(function() {
    // Function to get the id_karyawan from the URL path
    function getIdKaryawanFromPath() {
        const path = window.location.pathname;
        const pathParts = path.split('/');
        return pathParts[pathParts.length - 1]; // Assuming the id is the last part of the path
    }

    // Get id_karyawan from the URL path
    const id_karyawan = getIdKaryawanFromPath();

    $('#fuzzyButton').click(function() {
        if (id_karyawan) {
            $.ajax({
                url: `/admin/fuzzy/${id_karyawan}`,
                method: 'GET',
                success: function(data) {
                    $('#result').html(JSON.stringify(data));
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        } else {
            alert('ID Karyawan is not specified in the URL.');
        }
    });
});

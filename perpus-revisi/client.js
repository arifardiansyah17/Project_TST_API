$(function() {
    // Get the NIM value from the input field
    const nim = $('#loggedNim').val();
    const status = $('#loggedStatus').val();
  
    // Check if NIM is provided
    if (nim, status) {
        // Make an AJAX POST request to client.php
        $.ajax({
            url: 'client.php',
            method: 'POST',
            dataType: 'json',
            data: { nim, status } // Send the NIM as data
        })
        .done((result) => {
            console.log('AJAX Detailed Result:', result);

            // Check if there is an error in the result
            if (result.error) {
                $('#result').html(`
                    <div class="notification-box error">
                        <p>${result.error}</p>
                    </div>
                `);
            } else {
                // Display the maximum number of books that can be borrowed
                $('#result').html(`
                    <div class="notification-box success">
                        <p>Maks Buku yang Dapat Dipinjam: 10</p>
                    </div>
                `);
            }
  
        })
        .fail((xhr, status, error) => {
            console.error('AJAX Error Details:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });

            // Default error message
            let errorMessage = 'Terjadi kesalahan tidak dikenal';
            try {
                // Attempt to parse the error response
                const errorResponse = JSON.parse(xhr.responseText);
                errorMessage = errorResponse.error || errorMessage;
            } catch (parseError) {
                // If parsing fails, use the raw response or the error
                errorMessage = xhr.responseText || error;
            }

            // Display the error message
            $('#result').html(`
                <div class="notification-box error">
                    <p>Error: ${errorMessage}</p>
                </div>
            `);
        });
    } else {
        // If NIM is not found, display an error message
        $('#result').html(`
            <div class="notification-box error">
                <p>Error: NIM tidak ditemukan. Silakan login.</p>
            </div>
        `);
    }
});
$(function() {
  // Ambil NIM yang sudah login dari elemen tersembunyi
  const nim = $('#loggedNim').val();

  if (nim) {
      // Kirim request untuk mendapatkan status pengembalian buku
      $.ajax({
          url: 'client.php',  // Endpoint untuk menangani request
          method: 'POST',
          data: { nim }
      })
      .done((result) => {
          $('#result').html(`
              <div class="notification-box success">
                  <p>${result.message}</p>
              </div>
          `);

          // Menambahkan event klik untuk menghilangkan box
          $('.notification-box').on('click', function() {
              $(this).fadeOut(500);  // Menghilangkan box dengan animasi
          });
      })
      .fail(() => {
          $('#result').html(`
              <div class="notification-box error">
                  <p>Error: Unable to get notification. Please try again later.</p>
              </div>
          `);

          // Menambahkan event klik untuk menghilangkan box
          $('.notification-box').on('click', function() {
              $(this).fadeOut(500);  // Menghilangkan box dengan animasi
          });
      });
  } else {
      $('#result').html(`
          <div class="notification-box error">
              <p>Error: NIM not found. Please log in.</p>
          </div>
      `);
  }
});






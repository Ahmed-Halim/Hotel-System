function delete_hotel_image(image) {
  $.ajax({
    type: 'GET',
    url: './delete_hotel_image.php',
    data: 'image=' + image,
    async: false,
    success: function(data) {
      if (data == "done") {
        document.getElementById(image).outerHTML = "";
      }
    }
  });
}

$(document).ready(function() {

  $(".sidebar li a").each(function() {
    if (this.href == window.location.href) {
      $(this).addClass("activated");
    }
  });

  $("#add_image_upload").click(function() {
    $("#upload_images").html($("#upload_images").html() + '<label><input type="file" name="images[]" /></label>');
  });

});

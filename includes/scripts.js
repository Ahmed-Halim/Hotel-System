function setCookie(cname, cvalue) {
  var d = new Date();
  d.setTime(d.getTime() + (24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// Slideshow hotel images ** hotel page ** //
var SlideImages = document.getElementsByClassName("SlideImg");
var SlideImagesLength = SlideImages.length;
var SlideIndex = 0;
showSlide(0);

function plusSlide(Step) {
  if (SlideIndex + Step == SlideImagesLength) {
    SlideIndex = 0;
  } else if (SlideIndex + Step == -1) {
    SlideIndex = SlideImagesLength - 1;
  } else {
    SlideIndex += Step;
  }
  showSlide(SlideIndex);
}

function showSlide(n) {
  for (var i = 0; i < SlideImagesLength; i++) {
    if (i == n) {
      SlideImages[i].style.display = "block";
    } else {
      SlideImages[i].style.display = "none";
    }
  }
}

window.setInterval(function() {
  plusSlide(1);
}, 5000);



// Register valiation ** register page ** //
function RegisterValidation() {
  var FirstName = document.forms["register"]["FirstName"].value;
  var LastName = document.forms["register"]["LastName"].value;
  var Email = document.forms["register"]["Email"].value;
  var Password = document.forms["register"]["Password"].value;
  var Country = document.forms["register"]["Country"].options[document.getElementById("Country").selectedIndex].value;
  var Phone = document.forms["register"]["Phone"].value;
  var Error = document.getElementById("Error");
  var RegexOnlyCharacters = /^[a-zA-Z ]+$/;
  var RegexOnlyPhone = /^01[012][0-9]{8}/;
  var RegexOnlyEmail = /\S+@\S+\.\S+/;

  document.forms["register"]["FirstName"].classList.remove("RedBorder");
  document.forms["register"]["LastName"].classList.remove("RedBorder");
  document.forms["register"]["Email"].classList.remove("RedBorder");
  document.forms["register"]["Password"].classList.remove("RedBorder");
  document.forms["register"]["Country"].classList.remove("RedBorder");
  document.forms["register"]["Phone"].classList.remove("RedBorder");

  if (FirstName.length == 0) {
    document.forms["register"]["FirstName"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "First name is required";
    return false;
  }

  if (!FirstName.match(RegexOnlyCharacters)) {
    document.forms["register"]["FirstName"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "First name must be charcters only";
    return false;
  }

  if (LastName.length == 0) {
    document.forms["register"]["LastName"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Last name is required";
    return false;
  }

  if (!LastName.match(RegexOnlyCharacters)) {
    document.forms["register"]["LastName"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Last name must be charcters only";
    return false;
  }

  if (Email.length == 0) {
    document.forms["register"]["Email"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Email is required";
    return false;
  }

  if (!Email.match(RegexOnlyEmail)) {
    document.forms["register"]["Email"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Email format invalid. E.g example@domain.com";
    return false;
  }


  if (Password.length < 8) {
    document.forms["register"]["Password"].classList.add("RedBorder");
    Error.style.display = "block";
    if (Password.length == 0) {
      Error.innerHTML = "Password is required";
    } else {
      Error.innerHTML = "Password length must be grater than or equal than 8 characters";
    }
    return false;
  }

  if (Country == "") {
    document.forms["register"]["Country"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Select your country";
    return false;
  }

  if (Phone.length == 0) {
    document.forms["register"]["Phone"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Phone is required";
    return false;
  }

  if (!Phone.match(RegexOnlyPhone)) {
    document.forms["register"]["Phone"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Phone must start with 010 or 011 or 012 followed by 8 digits";
    return false;
  }

  Error.style.display = "none";
  return true;

}





// Login valiation ** login page ** //
function LoginValidation() {
  var Email = document.forms["login"]["Email"].value;
  var Password = document.forms["login"]["Password"].value;
  var Error = document.getElementById("Error");
  var RegexOnlyEmail = /\S+@\S+\.\S+/;

  document.forms["login"]["Email"].classList.remove("RedBorder");
  document.forms["login"]["Password"].classList.remove("RedBorder");

  if (Email.length == 0) {
    document.forms["login"]["Email"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Email is required";
    return false;
  }

  if (!Email.match(RegexOnlyEmail)) {
    document.forms["login"]["Email"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Email format invalid. E.g example@domain.com";
    return false;
  }


  if (Password.length == 0) {
    document.forms["login"]["Password"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Password is required";
    return false;
  }

  Error.style.display = "none";

}






// Visa valiation ** payment page ** //
function VisaValidation() {
  var CardNumber = document.forms["visa"]["number"].value;
  var ExpiredDate = document.forms["visa"]["expired"].value;
  var CVV = document.forms["visa"]["cvv"].value;
  var Error = document.getElementById("Error");
  var RegexOnlyNumbers = /^[0-9]+$/;
  var RegexOnlyMonthYear = /^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/;

  document.forms["visa"]["number"].classList.remove("RedBorder");
  document.forms["visa"]["expired"].classList.remove("RedBorder");
  document.forms["visa"]["cvv"].classList.remove("RedBorder");

  if (CardNumber.length == 0) {
    document.forms["visa"]["number"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Card number is required";
    return false;
  }

  if (!CardNumber.match(RegexOnlyNumbers)) {
    document.forms["visa"]["number"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Card number must be numbers only";
    return false;
  }

  if (CardNumber.length != 16) {
    document.forms["visa"]["number"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Card number must be 16 number";
    return false;
  }

  if (ExpiredDate.length == 0) {
    document.forms["visa"]["expired"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Expired date is required";
    return false;
  }


  if (!ExpiredDate.match(RegexOnlyMonthYear)) {
    document.forms["visa"]["expired"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "Expired date must be be in format MM/YY e.g 09/2024";
    return false;
  }

  if (CVV.length == 0) {
    document.forms["visa"]["cvv"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML ="CVV is required";
    return false;
  }

  if (!CVV.match(RegexOnlyNumbers)) {
    document.forms["visa"]["cvv"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "CVV must be numbers only";
    return false;
  }

  if (CVV.length != 3) {
    document.forms["visa"]["cvv"].classList.add("RedBorder");
    Error.style.display = "block";
    Error.innerHTML = "CVV must be 3 numbers";
    return false;
  }

  Error.style.display = "none";
}



// Search Valiation ** home page ** //
function SearchValiation() {
  var BookForm = document.forms["Book-Form"];
  var Error = document.getElementById("Error");
  var City = BookForm["City"].value;
  var Checkin = BookForm["Checkin"].value;
  var Nights = BookForm["Nights"].options[document.getElementById("Nights").selectedIndex].value;

  var CurrentDate = new Date();
  var RegexOnlyNumbers = /^[0-9]+$/;
  var RegexOnlyCharacters = /^[a-zA-Z ]+$/;


  if (City.length == 0) {
    Error.style.display = "block";
    Error.innerHTML = "You must enter a city name";
    return false;
  }


  if (!City.match(RegexOnlyCharacters)) {
    Error.style.display = "block";
    Error.innerHTML = "City must contains characters only";
    return false;
  }

  if (Checkin.length == 0) {
    Error.style.display = "block";
    Error.innerHTML = "Check in date is empty";
    return false;
  }

  if (new Date(Checkin) < CurrentDate) {
    Error.style.display = "block";
    Error.innerHTML = "Check in date is not valid. it can't be less than today's date";
    return false;
  }

  if (Nights == 0) {
    Error.style.display = "block";
    Error.innerHTML = "Select number of nights";
    return false;
  }

  Error.style.display = "none";

  setCookie("nights",Nights);
  setCookie("checkin",Checkin);

}




$( "#subcribption-form" ).submit(function( event ) {
  event.preventDefault();
  var RegexEmailOnly = /\S+@\S+\.\S+/;
  var email = $("#subscribe-input").val();
  if (RegexEmailOnly.test(email)) {
    $.ajax({
      type: 'POST',
      url: './subscribe.php',
      data: 'email=' + email,
      success: function(data) {
        if (data == "done") {
          $("#subcribption-form").hide();
          $("#subcribption-success").fadeIn(1000);
        } else {
          alert("Failed");
        }
      }
    });
  } else {
      alert("Failed");
  }
});

// Fill popup with text before showing it //
function fillPopupForm(type) {
  switch (type) {
    case "cookies":
    $("#popupTitle").html("What is Cookies ?");
    $("#popupBody").html($("#cookies_content").html());
    break;
    case "aboutus":
    $("#popupTitle").html("Who we are ?");
    $("#popupBody").html($("#aboutus_content").html());
    break;
    case "terms":
    $("#popupTitle").html("Terms & Conditions");
    $("#popupBody").html($("#terms_content").html());
    break;
    case "privacy":
    $("#popupTitle").html("Privacy Policy");
    $("#popupBody").html($("#privacy_policy_content").html());
    break;
    default:
    $("#popupTitle").html("");
    $("#popupBody").html("");

  }
}




// Jquery //
$(document).ready(function() {
  $("#menu li a").each(function() {
    if (this.href == window.location.href) {
      $(this).addClass("active");
    }
  });

  $("#filteration a").each(function() {
    if (this.href == window.location.href) {
      $(this).addClass("selected");
    }
  });

  $(".mobile-menu").click(function(){
    $("#menu ul").slideToggle();
  });


  var city = ["Hong Kong","Singapore","Bangkok","London","Macau","Kuala Lumpur","Shenzhen","New York City","Antalya","Paris","Istanbul","Rome","Dubai","Guangzhou","Phuket","Mecca","Pattaya","Taipei City","Prague","Shanghai","Las Vegas","Miami","Barcelona","Moscow","Beijing","Los Angeles","Budapest","Vienna","Amsterdam","Sofia","Madrid","Orlando","Ho Chi Minh City","Lima","Berlin","Tokyo","Warsaw","Chennai","Cairo","Nairobi","Hangzhou","Milan","San Francisco","Buenos Aires","Venice","Mexico City","Dublin","Seoul","Mugla","Mumbai","Denpasar","Delhi","Toronto","Zhuhai","St Petersburg","Burgas","Sydney","Djerba","Munich","Johannesburg","Cancun","Edirne","Suzhou","Bucharest","Punta Cana","Agra","Jaipur","Brussels","Nice","Chiang Mai","Sharm el-Sheikh","Lisbon","East Province","Marrakech","Jakarta","Manama","Hanoi","Honolulu","Manila","Guilin","Auckland","Siem Reap","Sousse","Amman","Vancouver","Abu Dhabi","Kiev","Doha","Florence","Rio de Janeiro","Melbourne","Washington D.C.","Riyadh","Christchurch","Frankfurt","Baku","Sao Paulo","Harare","Kolkata","Nanjing"];
  $( "#City" ).autocomplete({maxResults: 5, source: function(request, response) {
    var results = $.ui.autocomplete.filter(city, request.term);
    response(results.slice(0, this.options.maxResults));
  }
});

$("#checkin").datepicker({ dateFormat: 'yy-mm-dd' });
$("#checkindate").datepicker({ dateFormat: 'yy-mm-dd' });


window.onscroll = function() {
  if ($(window).scrollTop() == 0) {
    $("#top").fadeOut(400);
  } else {
    $("#top").fadeIn(400);
  }
}

$("#top").click(function() {
  return $("html, body").animate({
    scrollTop: 0
  }, 600), !1
});

$("#more").click(function() {
  fillPopupForm("cookies");
  $(".popupForm").fadeIn(1000);
  $(this).parent().fadeOut(1000);
});

$("#aboutus").click(function() {
  fillPopupForm("aboutus");
  $(".popupForm").fadeIn(1000);
});

$("#terms").click(function() {
  fillPopupForm("terms");
  $(".popupForm").fadeIn(1000);
});

$("#privacy").click(function() {
  fillPopupForm("privacy");
  $(".popupForm").fadeIn(1000);
});

$(".closeBtn").click(function() {
  $(this).parent().fadeOut(1000);
});

$("#filter").click(function() {
  $("#options").toggle(1000);
});

$("#make-reservation").click(function() {
  $("#Reservation").slideToggle();
});

var roomtype = false , checkin = false , nights = false;

function Ready() {
  return roomtype != false && checkin != false && nights != false;
}

function UpdateCart(RoomType , Nights) {
  var Price = $("#" + RoomType + "-Price").html();
  var RoomPrice = Nights * Price;
  var AddedValueTax = Math.round(0.14 * RoomPrice);
  var TotalPrice = RoomPrice + AddedValueTax;

  $("#payment").val(TotalPrice);
  $("#numberNights").html(Nights);
  $("#roomPrice").html(Nights + " x " + Price + " = " + RoomPrice);
  $("#addedValuePrice").html(RoomPrice + " x " + 0.14 + " = " + AddedValueTax);
  $("#totalDue").html(TotalPrice);

}


nights = $("#nights").val();
if (new Date($("#checkin").val()) < new Date()) {
  checkin = false;
  $("#checkin").addClass("RedBorder");
  $("#Error").html("Check in date must be greater that current date");
  $("#Error").fadeIn();
  $("#Success").fadeOut();
} else {
  checkin = $("#checkin").val();
  $("#checkin").removeClass("RedBorder");
  $("#Error").fadeOut();
}

if (Ready()) {
  UpdateCart(roomtype , nights);
  $("#Success").fadeIn();
}


$("#checkin").change(function() {
  if (new Date($(this).val()) < new Date()) {
    checkin = false;
    $("#checkin").addClass("RedBorder");
    $("#Error").html("Check in date must be greater that current date");
    $("#Error").fadeIn();
    $("#Success").fadeOut();
  } else {
    checkin = $(this).val();
    $("#checkin").removeClass("RedBorder");
    $("#Error").fadeOut();
  }
  if (Ready()) {
    UpdateCart(roomtype , nights);
    $("#Success").fadeIn();
  }
});

$("#nights").change(function() {
  nights = $(this).val();
  if (Ready()) {
    UpdateCart(roomtype , nights);
    $("#Success").fadeIn();
  }
});


$(".RoomType").click(function(){
  $(".RoomType").removeClass("Selected");
  $(this).addClass("Selected");
  roomtype = $(this).html();
  $("#room").val($(this).html());
  if (Ready()) {
    UpdateCart(roomtype , nights);
    $("#Success").fadeIn();
  }
});



$("#ReservationID").html("Reservation Number #" + Math.floor(Math.random() * 1000));

$('.question').on("click", function(){
  $(this).next().slideToggle(500).siblings('.answer').slideUp();
  var i = $(this).children('i');
  $('i').not(i).removeClass('rotate');
  i.toggleClass('rotate');

});

$("#search-btn").bind("click", SearchValiation);
$("#payment-btn").bind("click", VisaValidation);
$("#register-btn").bind("click", RegisterValidation);
$("#login-btn").bind("click", LoginValidation);

});

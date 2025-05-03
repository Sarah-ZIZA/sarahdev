$(document).ready(function () {
  $("#menu").click(function () {
    $(this).toggleClass("fa-times");
    $(".navbar").toggleClass("nav-toggle");
  });

  $(window).on("scroll load", function () {
    $("#menu").removeClass("fa-times");
    $(".navbar").removeClass("nav-toggle");

    if (window.scrollY > 60) {
      document.querySelector("#scroll-top").classList.add("active");
    } else {
      document.querySelector("#scroll-top").classList.remove("active");
    }
  });
});

document.addEventListener("visibilitychange", function () {
  if (document.visibilityState === "visible") {
    document.title = "Portfolio SARAH";
    $("#favicon").attr("href", "assets/images/AVATAR.png");
  } else {
    document.title = "Retour sur mon Portfolio";
    $("#favicon").attr("href", "assets/images/avatar1.png");
  }
});

// fetch projects start
function getProjects() {
  return fetch("projects.json")
    .then((response) => response.json())
    .then((data) => {
      return data;
    });
}

function showProjects(projects) {
  let projectsContainer = document.querySelector(".work .box-container");
  let projectsHTML = "";
  projects.forEach((project) => {
    projectsHTML += `
        <div class="grid-item ${project.category}">
        <div class="box tilt" style="width: 380px; margin: 1rem">
      <img draggable="false" src="../assets/images/projects/${project.image}.png" alt="project" />
      <div class="content">
        <div class="tag">
        <h3>${project.name}</h3>
        </div>
        <div class="desc">
          <p>${project.desc}</p>
          <div class="btns">
            <a href="${project.links.view}" class="btn" target="_blank"><i class="fas fa-eye"></i>Visiter</a>
          
          </div>
        </div>
      </div>
    </div>
    </div>`;
  });
  projectsContainer.innerHTML = projectsHTML;

  // produits de filtres isotopiques
  var $grid = $(".box-container").isotope({
    // itemSelector: ".grid-item",
    // // layoutMode: "fitRows",
    masonry: {
      columnWidth: 200,
    },
  });

  // filter items on button click
  $(".button-group").on("click", "button", function () {
    $(".button-group").find(".is-checked").removeClass("is-checked");
    $(this).addClass("is-checked");
    const filterValue = $(this).attr("data-filter").split(", ").join(", ");
    $grid.isotope({ filter: filterValue });
  });
}

getProjects().then((data) => {
  showProjects(data);
});
// fetch projects end
const toggleButton = document.getElementById("theme-toggle");
const themeIcon = document.getElementById("theme-icon");
const body = document.body;

// Vérifie l'état du mode sombre dans le stockage local
if (localStorage.getItem("theme") === "dark") {
  body.classList.add("dark-mode");
  themeIcon.src = "../assets/images/moon.png"; // Icône de la lune pour le mode sombre
  themeIcon.alt = "Mode sombre";
} else {
  body.classList.remove("dark-mode");
  themeIcon.src = "../assets/images/sun.png"; // Icône du soleil pour le mode clair
  themeIcon.alt = "Mode clair";
}

// Lorsque le bouton est cliqué, bascule entre les modes
toggleButton.addEventListener("click", () => {
  body.classList.toggle("dark-mode");

  // Enregistre l'état du mode dans le stockage local
  if (body.classList.contains("dark-mode")) {
    localStorage.setItem("theme", "dark");
    themeIcon.src = "../assets/images/moon.png"; // Icône de la lune
    themeIcon.alt = "Mode sombre";
  } else {
    localStorage.setItem("theme", "light");
    themeIcon.src = "../assets/images/sun.png"; // Icône du soleil
    themeIcon.alt = "Mode clair";
  }
});
// désactiver le mode développeur
document.onkeydown = function (e) {
  if (e.keyCode == 123) {
    return false;
  }
  if (e.ctrlKey && e.shiftKey && e.keyCode == "I".charCodeAt(0)) {
    return false;
  }
  if (e.ctrlKey && e.shiftKey && e.keyCode == "C".charCodeAt(0)) {
    return false;
  }
  if (e.ctrlKey && e.shiftKey && e.keyCode == "J".charCodeAt(0)) {
    return false;
  }
  if (e.ctrlKey && e.keyCode == "U".charCodeAt(0)) {
    return false;
  }
};

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

    // scroll spy
    $("section").each(function () {
      let height = $(this).height();
      let offset = $(this).offset().top - 200;
      let top = $(window).scrollTop();
      let id = $(this).attr("id");

      if (top > offset && top < offset + height) {
        $(".navbar ul li a").removeClass("active");
        $(".navbar").find(`[href="#${id}"]`).addClass("active");
      }
    });
  });

  // smooth scrolling
  $('a[href*="#"]').on("click", function (e) {
    e.preventDefault();
    $("html, body").animate(
      {
        scrollTop: $($(this).attr("href")).offset().top,
      },
      500,
      "linear"
    );
  });
});

document.addEventListener("visibilitychange", function () {
  if (document.visibilityState === "visible") {
    document.title = "Portfolio SARAH";
    $("#favicon").attr("href", "assets/images/Bitmoji3.png");
  } else {
    document.title = "Portefolio SARAH";
    $("#favicon").attr("href", "assets/images/Bitmoji3.png");
  }
});

// <!-- typed js effect starts -->
var typedName = new Typed(".typing-name", {
  strings: ["Moi, c'est Sarah !"],
  typeSpeed: 100,
  backSpeed: 50,
  loop: true,
  backDelay: 1000,
});

var typedText = new Typed(".typing-text", {
  strings: ["diplomée d'un BTS SIO", "en licence 3 Informatique"],
  typeSpeed: 100,
  backSpeed: 50,
  loop: true,
  backDelay: 500,
});
// <!-- typed js effect ends -->

async function fetchData(type = "skills") {
  let response;
  type === "skills"
    ? (response = await fetch("skills.json"))
    : (response = await fetch("projects.json"));
  const data = await response.json();
  return data;
}

function showSkills(skills) {
  let skillsContainer = document.getElementById("skillsContainer");
  let skillHTML = "";

  skills.forEach((skill) => {
    skillHTML += `
    <div class="bar">
      <div class="info">
        <img src="${skill.icon}" alt="skill" />
        <span>${skill.name}</span>`;

    if (skill.details && skill.details.length > 0) {
      skillHTML += `<ul>`;
      skill.details.forEach((detail) => {
        skillHTML += `<li>${detail}</li>`;
      });
      skillHTML += `</ul>`;
    }

    if (skill.link) {
      skillHTML += `<a href="${skill.link}" target="_blank"><span>${skill.linkText}</span></a>`;
    }

    skillHTML += `
      </div>
    </div>`;
  });

  skillsContainer.innerHTML = skillHTML;
}

function showProjects(projects) {
  let projectsContainer = document.querySelector("#work .box-container");
  let projectHTML = "";
  projects
    .slice(0, 10)
    .filter((project) => project.category != "TP")
    .forEach((project) => {
      projectHTML += `
        <div class="box tilt">
      <img draggable="false" src="assets/images/projects/${project.image}.png" alt="project" />
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
    </div>`;
    });
  projectsContainer.innerHTML = projectHTML;

  /* ===== SCROLL REVEAL ANIMATION ===== */
  const srtop = ScrollReveal({
    origin: "top",
    distance: "80px",
    duration: 1000,
    reset: true,
  });

  /* SCROLL PROJECTS */
  srtop.reveal(".work .box", { interval: 200 });
}

fetchData().then((data) => {
  showSkills(data);
});

fetchData("projects").then((data) => {
  showProjects(data);
});

/* ===== SCROLL REVEAL ANIMATION ===== */
const srtop = ScrollReveal({
  origin: "top",
  distance: "80px",
  duration: 1000,
  reset: true,
});

/* SCROLL HOME */
srtop.reveal(".home .content h3", { delay: 200 });
srtop.reveal(".home .content p", { delay: 200 });
srtop.reveal(".home .content .btn", { delay: 200 });

srtop.reveal(".home .image", { delay: 400 });
srtop.reveal(".home .linkedin", { interval: 600 });
srtop.reveal(".home .github", { interval: 800 });

/* SCROLL ABOUT */
srtop.reveal(".about .content h3", { delay: 200 });
srtop.reveal(".about .content .tag", { delay: 200 });
srtop.reveal(".about .content p", { delay: 200 });
srtop.reveal(".about .content .box-container", { delay: 200 });
srtop.reveal(".about .content .resumebtn", { delay: 200 });

/* SCROLL SKILLS */
srtop.reveal(".skills .container", { interval: 200 });
srtop.reveal(".skills .container .bar", { delay: 400 });

/* SCROLL EDUCATION */
srtop.reveal(".education .box", { interval: 200 });

/* SCROLL PROJECTS */
srtop.reveal(".work .box", { interval: 200 });

/* SCROLL EXPERIENCE */
srtop.reveal(".experience .timeline", { delay: 400 });
srtop.reveal(".experience .timeline .container", { interval: 400 });

/* SCROLL CONTACT */
srtop.reveal(".contact .container", { delay: 400 });
srtop.reveal(".contact .container .form-group", { delay: 400 });

// EmailJS SDK

document.addEventListener("DOMContentLoaded", function () {
  emailjs.init("mEVkWNktYXFq74I_n"); // "YOUR_USER_ID" l'API key publique EmailJS

  document
    .getElementById("contact-form")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Récupérer les données du formulaire
      const serviceID = "service_ajftt8c"; //  votre identifiant de service EmailJS
      const templateID = "template_wgy6x8s"; // votre identifiant de modèle EmailJS

      emailjs.sendForm(serviceID, templateID, this).then(
        () => {
          alert("Votre demande de contact a été prise en compte.");
          document.getElementById("contact-form").reset();
        },
        (err) => {
          alert(
            "Une erreur est survenue lors de l'envoi du message : " +
              JSON.stringify(err)
          );
        }
      );
    });
});
const toggleButton = document.getElementById("theme-toggle");
const themeIcon = document.getElementById("theme-icon");
const body = document.body;

// Vérifie l'état du mode sombre dans le stockage local
if (localStorage.getItem("theme") === "dark") {
  body.classList.add("dark-mode");
  themeIcon.src = "assets/images/moon.png"; // Icône de la lune pour le mode sombre
  themeIcon.alt = "Mode sombre";
} else {
  body.classList.remove("dark-mode");
  themeIcon.src = "assets/images/sun.png"; // Icône du soleil pour le mode clair
  themeIcon.alt = "Mode clair";
}

// Lorsque le bouton est cliqué, bascule entre les modes
toggleButton.addEventListener("click", () => {
  body.classList.toggle("dark-mode");

  // Enregistre l'état du mode dans le stockage local
  if (body.classList.contains("dark-mode")) {
    localStorage.setItem("theme", "dark");
    themeIcon.src = "assets/images/moon.png"; // Icône de la lune
    themeIcon.alt = "Mode sombre";
  } else {
    localStorage.setItem("theme", "light");
    themeIcon.src = "assets/images/sun.png"; // Icône du soleil
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

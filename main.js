const menuToggle = document.getElementById("menuToggle");
const navLinks = document.getElementById("navLinks");

menuToggle.addEventListener("click", () => {
  navLinks.classList.toggle("open");
});



// Scroll Animation
const animatedElements = document.querySelectorAll(".animate");

window.addEventListener("scroll", () => {
  animatedElements.forEach(el => {
    const position = el.getBoundingClientRect().top;
    const windowHeight = window.innerHeight;

    if (position < windowHeight - 100) {
      el.classList.add("show");
    }
  });
});

// Trigger scroll event once on page load
window.dispatchEvent(new Event("scroll"));


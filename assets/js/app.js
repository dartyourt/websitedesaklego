document.addEventListener("DOMContentLoaded", function () {
  const btnMenu = document.getElementById("btn-menu");
  const mobileMenu = document.getElementById("mobile-menu");
  const mobileLinks = document.querySelectorAll(".mobile-link");

  if (btnMenu && mobileMenu) {
    btnMenu.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden");
    });
  }

  mobileLinks.forEach(link => {
    link.addEventListener("click", () => {
      mobileMenu.classList.add("hidden");
    });
  });
});
/* =====================
   AUTO SLIDER PERANGKAT
===================== */
document.addEventListener("DOMContentLoaded", () => {
  const slider = document.querySelector(".perangkat-slider");
  if (!slider) return;

  let scrollAmount = 0;
  const speed = 1; // makin besar makin cepat

  function autoSlide() {
    scrollAmount += speed;
    slider.scrollLeft = scrollAmount;

    // jika sudah mentok kanan → kembali ke awal
    if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth) {
      scrollAmount = 0;
    }
  }

  setInterval(autoSlide, 30);
});

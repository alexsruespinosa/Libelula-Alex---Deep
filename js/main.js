// === Libélula Website JS ===

// Inicializar Feather Icons
feather.replace();

// Animación simple en scroll o carga
document.addEventListener("DOMContentLoaded", () => {
  anime({
    targets: 'h1, h2, h3, p',
    opacity: [0, 1],
    translateY: [20, 0],
    easing: 'easeOutExpo',
    duration: 1000,
    delay: anime.stagger(100)   
  });
});

 /* 
 
 Menú móvil (básico)
document.getElementById("menu-toggle")?.addEventListener("click", () => {
  alert("We are working for you! (Coming Soon...)");
}); */

const products = () => Array.from(document.querySelectorAll('#products .card'));
let visibleLimit = 8;

function refreshProducts() {
  const shown = products().filter((card) => card.style.display !== 'none');

  shown.forEach((card, index) => {
    card.classList.toggle('is-hidden', index >= visibleLimit);
  });

  const visibleCount = document.getElementById('visibleCount');
  if (visibleCount) visibleCount.textContent = String(Math.min(shown.length, visibleLimit));

  const loadMoreBtn = document.getElementById('loadMoreBtn');
  if (loadMoreBtn) loadMoreBtn.style.display = shown.length > visibleLimit ? 'inline-flex' : 'none';
}

function searchProduct() {
  const value = (document.getElementById('search')?.value || '').toLowerCase().trim();

  products().forEach((card) => {
    const title = card.querySelector('h3')?.innerText.toLowerCase() || '';
    card.style.display = title.includes(value) ? '' : 'none';
  });

  visibleLimit = 8;
  refreshProducts();
}

function sortProducts() {
  const wrapper = document.getElementById('products');
  const type = document.getElementById('sortBy')?.value;
  if (!wrapper || !type) return;

  const sorted = products().sort((a, b) => {
    const priceA = Number(a.dataset.prix || 0);
    const priceB = Number(b.dataset.prix || 0);
    const viewsA = Number(a.dataset.views || 0);
    const viewsB = Number(b.dataset.views || 0);

    if (type === 'prixAsc') return priceA - priceB;
    if (type === 'prixDesc') return priceB - priceA;
    if (type === 'viewsDesc') return viewsB - viewsA;
    return 0;
  });

  sorted.forEach((card) => wrapper.appendChild(card));
  refreshProducts();
}

function toggleProductsView() {
  const wrapper = document.querySelector('.products');
  const btn = document.getElementById('viewToggleBtn');
  if (!wrapper || !btn) return;

  wrapper.classList.toggle('list-mode');
  btn.textContent = wrapper.classList.contains('list-mode') ? 'Passer en grille' : 'Passer en liste';
}

function loadMoreProducts() {
  visibleLimit += 8;
  refreshProducts();
}

function initReveal() {
  const items = document.querySelectorAll('.reveal');
  if (!items.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });

  items.forEach((item) => observer.observe(item));
}

document.addEventListener('DOMContentLoaded', () => {
  refreshProducts();
  initReveal();
});

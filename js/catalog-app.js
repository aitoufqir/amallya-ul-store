const { useEffect, useMemo, useState } = React;

function formatPrice(value) {
  return `${new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(value)} DH`;
}

function App() {
  const bootstrap = window.__CATALOG_BOOTSTRAP__ || { products: [], categories: [], initialCategory: '' };
  const [products, setProducts] = useState(bootstrap.products || []);
  const [search, setSearch] = useState('');
  const [sortBy, setSortBy] = useState('default');
  const [listMode, setListMode] = useState(false);
  const [visibleLimit, setVisibleLimit] = useState(8);
  const [activeCategory, setActiveCategory] = useState(bootstrap.initialCategory || '');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const items = document.querySelectorAll('.reveal');
    if (!items.length) return undefined;

    items.forEach((item, index) => {
      item.style.setProperty('--reveal-delay', `${index * 90}ms`);
    });

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });

    items.forEach((item) => observer.observe(item));
    return () => observer.disconnect();
  }, [products, listMode, visibleLimit]);

  useEffect(() => {
    if (activeCategory === (bootstrap.initialCategory || '')) {
      return;
    }

    let cancelled = false;
    setLoading(true);

    fetch(`api/products.php${activeCategory ? `?cat=${encodeURIComponent(activeCategory)}` : ''}`)
      .then((response) => response.json())
      .then((data) => {
        if (cancelled) return;
        setProducts(Array.isArray(data.products) ? data.products : []);
        setVisibleLimit(8);
      })
      .catch(() => {
        if (!cancelled) {
          setProducts([]);
        }
      })
      .finally(() => {
        if (!cancelled) {
          setLoading(false);
        }
      });

    const url = activeCategory ? `?cat=${encodeURIComponent(activeCategory)}` : window.location.pathname;
    window.history.replaceState({}, '', url);

    return () => {
      cancelled = true;
    };
  }, [activeCategory]);

  const filteredProducts = useMemo(() => {
    const term = search.trim().toLowerCase();
    const base = term
      ? products.filter((product) => product.name.toLowerCase().includes(term))
      : [...products];

    if (sortBy === 'prixAsc') {
      base.sort((a, b) => a.price - b.price);
    } else if (sortBy === 'prixDesc') {
      base.sort((a, b) => b.price - a.price);
    } else if (sortBy === 'viewsDesc') {
      base.sort((a, b) => b.views - a.views);
    }

    return base;
  }, [products, search, sortBy]);

  const visibleProducts = filteredProducts.slice(0, visibleLimit);
  const maxPrice = filteredProducts.length ? Math.max(...filteredProducts.map((product) => product.price)) : 0;
  const minPrice = filteredProducts.length ? Math.min(...filteredProducts.map((product) => product.price)) : 0;

  return (
    <main className="page-shell wide">
      <section className="page-banner reveal">
        <div className="banner-grid">
          <div className="banner-copy">
            <p className="eyebrow">Catalogue</p>
            <h1>Toutes les pieces en pleine largeur.</h1>
            <p className="section-copy">Le catalogue occupe mieux l'ecran avec plus d'air, plus de presence et une lecture plus confortable.</p>
          </div>
          <div className="banner-panel">
            <span className="panel-tag">Vue catalogue</span>
            <div className="banner-stats">
              <div className="banner-stat"><strong>{filteredProducts.length}</strong><span>resultats</span></div>
              <div className="banner-stat"><strong>{bootstrap.categories.length}</strong><span>categories</span></div>
              <div className="banner-stat"><strong>{visibleProducts.length}</strong><span>visibles</span></div>
            </div>
          </div>
        </div>
      </section>

      <section className="stats-grid reveal">
        <article className="stat-card"><strong>{filteredProducts.length}</strong><span>Produits visibles</span></article>
        <article className="stat-card"><strong>{bootstrap.categories.length}</strong><span>Categories</span></article>
        <article className="stat-card"><strong>{visibleProducts.length}</strong><span>Affiches maintenant</span></article>
      </section>

      <section className="category-section reveal">
        <div className="section-heading">
          <p className="eyebrow">Filtre rapide</p>
          <h2 className="page-title">Choisis une categorie</h2>
        </div>
        <div className="category-grid">
          <button type="button" className={`category-card${activeCategory === '' ? ' is-active' : ''}`} onClick={() => setActiveCategory('')}>
            <strong>Tous</strong>
            <span>Retour au catalogue complet.</span>
          </button>
          {(bootstrap.categories || []).map((category) => (
            <button
              type="button"
              className={`category-card${activeCategory === category.value ? ' is-active' : ''}`}
              key={category.value}
              onClick={() => setActiveCategory(category.value)}
            >
              <strong>{category.label}</strong>
              <span>{category.copy}</span>
            </button>
          ))}
        </div>
      </section>

      <section className="actions-bar reveal">
        <div className="action-item">
          <input
            id="search"
            type="text"
            placeholder="Rechercher..."
            value={search}
            onChange={(event) => {
              setSearch(event.target.value);
              setVisibleLimit(8);
            }}
          />
        </div>
        <div className="action-item">
          <label htmlFor="sortBy">Trier par</label>
          <select id="sortBy" value={sortBy} onChange={(event) => setSortBy(event.target.value)}>
            <option value="default">Par defaut</option>
            <option value="prixAsc">Prix croissant</option>
            <option value="prixDesc">Prix decroissant</option>
            <option value="viewsDesc">Plus populaires</option>
          </select>
        </div>
        <div className="action-item">
          <button className="btn-outline" type="button" onClick={() => setListMode((value) => !value)}>
            {listMode ? 'Passer en grille' : 'Passer en liste'}
          </button>
        </div>
        <div className="action-item stats">Total: <strong>{filteredProducts.length}</strong></div>
      </section>

      {filteredProducts.length > 0 && (
        <section className="catalog-summary reveal">
          <div className="summary-card"><span className="summary-label">Articles affiches</span><strong>{visibleProducts.length}</strong></div>
          <div className="summary-card"><span className="summary-label">Catalogue total</span><strong>{filteredProducts.length}</strong></div>
          <div className="summary-card"><span className="summary-label">Plage de prix</span><strong>{formatPrice(minPrice)} - {formatPrice(maxPrice)}</strong></div>
        </section>
      )}


      <section className="products-shell reveal">
      <section className={`products${listMode ? ' list-mode' : ''}`} id="products">
        {loading && <article className="card"><h3>Chargement...</h3><p className="meta">Mise a jour du catalogue en cours.</p></article>}
        {!loading && visibleProducts.map((product) => (
          <article className="card" key={product.id}>
            <img src={product.image} alt={product.name} onError={(event) => { event.currentTarget.onerror = null; event.currentTarget.src = 'images/pic1.png'; }} />
            <p className="category-chip">{product.category}</p>
            <h3>{product.name}</h3>
            {product.description && <p className="meta product-description">{product.description}</p>}
            <div className="product-specs">
              <span>Taille: {product.size}</span>
              <span>Marque: {product.brand}</span>
            </div>
            <div className="card-footer">
              <p className="price">{formatPrice(product.price)}</p>
              <a href={product.addToCartUrl} className="btn-primary">Ajouter</a>
            </div>
          </article>
        ))}
      </section>
      </section>

      {!loading && filteredProducts.length === 0 && (
        <section className="no-products reveal is-visible">
          <div className="empty-icon">•</div>
          <h3>Aucun produit trouve</h3>
          <p>Essaie une autre recherche ou change la categorie active.</p>
        </section>
      )}

      {visibleProducts.length < filteredProducts.length && (
        <div className="load-more-wrap">
          <button type="button" className="btn-primary" onClick={() => setVisibleLimit((value) => value + 8)}>
            Voir plus
          </button>
        </div>
      )}
    </main>
  );
}

const root = ReactDOM.createRoot(document.getElementById('catalogApp'));
root.render(<App />);

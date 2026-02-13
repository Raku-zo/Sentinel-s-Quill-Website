const categoryNames = {
  'news': 'All News',
  'news-campus': 'News - Campus',
  'news-sports': 'News - Sports',
  'news-scitech': 'News - Science & Technology',
  'news-local': 'News - Local',
  'news-foreign': 'News - Foreign',
  'feature': 'Feature',
  'editorial': 'Editorial',
  'column': 'Column',
  'photojournalism': 'Photojournalism',
  'broadcast': 'Broadcast Media',
  'literary': 'Literary'
};

async function loadCategoryArticles() {
  const urlParams = new URLSearchParams(window.location.search);
  const category = window.STATIC_CATEGORY || urlParams.get('type');
  
  if (!category) {
    window.location.href = '/index.html';
    return;
  }
  
  const categoryTitle = document.getElementById('category-title');
  categoryTitle.textContent = categoryNames[category] || category.charAt(0).toUpperCase() + category.slice(1);
  
  const navLinks = document.querySelectorAll('.nav a');
  navLinks.forEach(link => {
    if (link.getAttribute('data-category') === category) {
      link.classList.add('active');
    }
  });
  
  try {
    const response = await fetch(`/api/articles?category=${category}`);
    if (!response.ok) throw new Error("API not available");
    const articles = await response.json();
    
    const feedContainer = document.getElementById('news-feed');
    
    if (articles.length === 0) {
      feedContainer.innerHTML = `<p class="loading">No ${category} articles found yet.</p>`;
      return;
    }
    
    feedContainer.innerHTML = articles.map(article => createArticleCard(article)).join('');
  } catch (error) {
    console.log("Static site mode - no backend API");
    document.getElementById('news-feed').innerHTML = 
      `<p class="loading">.</p>`;
  }
}

const displayCategoryNames = {
  'news': 'News',
  'news-campus': 'Campus',
  'news-sports': 'Sports',
  'news-scitech': 'Sci & Tech',
  'news-local': 'Local',
  'news-foreign': 'Foreign',
  'feature': 'Feature',
  'editorial': 'Editorial',
  'column': 'Column',
  'photojournalism': 'Photo',
  'broadcast': 'Broadcast',
  'literary': 'Literary'
};

function createArticleCard(article) {
  const imageHtml = article.imageUrl 
    ? `<img src="${article.imageUrl}" alt="${article.title}">`
    : `<div class="article-image no-image">📰</div>`;
  
  const date = new Date(article.createdAt).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
  
  const displayCategory = displayCategoryNames[article.category] || article.category;
  
  return `
    <div class="article-card">
      <div class="article-image">
        ${imageHtml}
      </div>
      <div class="article-content">
        <span class="category-badge">${displayCategory}</span>
        <a href="/article.html?id=${article.id}" class="article-title">
          <h3>${article.title}</h3>
        </a>
        <div class="article-meta">
          By ${article.authorName} | ${date}
        </div>
        <p class="article-excerpt">${article.excerpt}</p>
        <a href="/article.html?id=${article.id}" class="read-more">
          Read More →
        </a>
      </div>
    </div>
  `;
}

document.addEventListener('DOMContentLoaded', loadCategoryArticles);
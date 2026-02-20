async function loadArticle() {
  const urlParams = new URLSearchParams(window.location.search);
  const articleId = urlParams.get('id');
  
  if (!articleId) {
    window.location.href = '/';
    return;
  }
  
  try {
    const response = await fetch(`/api/articles/${articleId}`);
    const article = await response.json();
    
    if (!article || article.error) {
      document.getElementById('article-container').innerHTML = '<p class="loading">Article not found.</p>';
      return;
    }
    
    const imageHtml = article.imageUrl 
      ? `<div class="article-image"><img src="${article.imageUrl}" alt="${article.title}"></div>`
      : '';
    
    const date = new Date(article.createdAt).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
    
    document.title = `${article.title} - The Sentinel's Quill`;
    
    document.getElementById('article-container').innerHTML = `
      <div class="article-header">
        <span class="category-badge">${article.category}</span>
        <h1 class="article-title" style="font-size: 2.5rem; margin-top: 0.5rem;">${article.title}</h1>
        <div class="article-meta">
          By ${article.authorName} | ${date}
        </div>
      </div>
      ${imageHtml}
      <div class="article-body">
        ${article.content.split('\n').map(p => `<p>${p}</p>`).join('')}
      </div>
    `;
  } catch (error) {
    console.error('Error loading article:', error);
    document.getElementById('article-container').innerHTML = '<p class="loading">Error loading article. Please try again later.</p>';
  }
}

document.addEventListener('DOMContentLoaded', loadArticle);

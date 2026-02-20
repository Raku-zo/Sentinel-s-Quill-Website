const categoryNames = {
  news: "News",
  "news-campus": "Campus",
  "news-sports": "Sports",
  "news-scitech": "Sci & Tech",
  "news-local": "Local",
  "news-foreign": "Foreign",
  feature: "Feature",
  editorial: "Editorial",
  column: "Column",
  photojournalism: "Photo",
  broadcast: "Broadcast",
  literary: "Literary",
};

async function loadArticles() {
  try {
    const response = await fetch("/api/articles");
    if (!response.ok) throw new Error("API not available");
    const articles = await response.json();

    const feedContainer = document.getElementById("news-feed");

    if (articles.length === 0) {
      feedContainer.innerHTML =
        '<p class="loading">No articles found. Staff members can log in to post content.</p>';
      return;
    }

    feedContainer.innerHTML = articles
      .map((article) => createArticleCard(article))
      .join("");
  } catch (error) {
    console.log("Static site mode - no backend API");
    document.getElementById("news-feed").innerHTML =
      '<p class="loading">Welcome! Browse articles using the navigation menu above.</p>';
  }
}

function createArticleCard(article) {
  const imageHtml = article.imageUrl
    ? `<img src="${article.imageUrl}" alt="${article.title}">`
    : `<div class="article-image no-image">📰</div>`;

  const date = new Date(article.createdAt).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });

  const displayCategory = categoryNames[article.category] || article.category;

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

document.addEventListener("DOMContentLoaded", loadArticles);
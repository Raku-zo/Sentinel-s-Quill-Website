const quill = new Quill('#editor', {
  theme: 'snow',
  modules: {
    toolbar: [
      [{ 'header': [1, 2, 3, false] }],
      ['bold', 'italic', 'underline', 'strike'],
      ['blockquote', 'code-block'],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      [{ 'indent': '-1'}, { 'indent': '+1' }],
      ['link', 'image'],
      ['clean']
    ]
  },
  placeholder: 'Write your article content here...'
});

const form = document.getElementById('article-form');
const successMessage = document.getElementById('success-message');
const errorMessage = document.getElementById('error-message');
const submitBtn = form.querySelector('.submit-btn');

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const content = quill.root.innerHTML;
  const plainText = quill.getText().trim();
  
  if (!plainText) {
    showError('Please write some content for the article.');
    return;
  }
  
  submitBtn.disabled = true;
  submitBtn.textContent = 'Publishing...';
  
  const formData = new FormData(form);
  formData.set('content', content);
  formData.set('excerpt', plainText.substring(0, 200) + '...');
  
  try {
    const response = await fetch('/api/articles', {
      method: 'POST',
      body: formData
    });
    
    const result = await response.json();
    
    if (response.ok) {
      showSuccess('Article published successfully! Redirecting...');
      form.reset();
      quill.setContents([]);
      
      setTimeout(() => {
        window.location.href = `/article.html?id=${result.id}`;
      }, 2000);
    } else {
      throw new Error(result.error || 'Failed to publish article');
    }
  } catch (error) {
    showError(error.message);
    submitBtn.disabled = false;
    submitBtn.textContent = 'Publish Article';
  }
});

function showSuccess(message) {
  successMessage.textContent = message;
  successMessage.style.display = 'block';
  errorMessage.style.display = 'none';
}

function showError(message) {
  errorMessage.textContent = message;
  errorMessage.style.display = 'block';
  successMessage.style.display = 'none';
}

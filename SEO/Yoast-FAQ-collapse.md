```css
.schema-faq-answer {
  display: none; /* Hide answers initially */
  padding-left: 20px;
  font-size: 14px;
  color: #555;
}

.schema-faq-question {
  cursor: pointer;
  padding: 10px;
  background-color: #f2f2f2;
  margin: 5px 0;
  font-weight: bold;
  border: 1px solid #ddd;
  border-radius: 5px;
  display: flex;
  align-items: center;
  position: relative;
}

/* Pseudo-element for the arrow */
.schema-faq-question::after {
  content: '\2193'; /* Unicode for down arrow (↓) */
  font-size: 16px;
  margin-right: 10px;
  transition: transform 0.3s ease;
  position: absolute;
  right: 0;
}

/* Active state styling */
.schema-faq-section.active .schema-faq-question::after {
  transform: rotate(180deg); /* Rotate the arrow when active */
}

.schema-faq-section.active .schema-faq-answer {
  display: block; /* Show the answer when active */
}

```

```js
document.addEventListener('DOMContentLoaded', function () {
  const faqSections = document.querySelectorAll('.schema-faq-section');

  faqSections.forEach(function (section) {
    const question = section.querySelector('.schema-faq-question');
    question.addEventListener('click', function () {
      const answer = section.querySelector('.schema-faq-answer');

      section.classList.toggle('active');
      
      // Toggle visibility of the answer
      if (answer.style.display === 'none' || answer.style.display === '') {
        answer.style.display = 'block';
      } else {
        answer.style.display = 'none';
      }
    });
  });
});
```

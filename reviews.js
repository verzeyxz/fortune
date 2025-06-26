// reviews.js

document.addEventListener('DOMContentLoaded', () => {
    const reviewsContainer = document.getElementById('reviews-container');

    // ตรวจสอบว่ามี container นี้ในหน้าเว็บหรือไม่
    if (!reviewsContainer) {
        return;
    }

    // ฟังก์ชันสำหรับสร้างดาว
    const createStars = (count) => {
        let starsHTML = '';
        for (let i = 0; i < 5; i++) {
            starsHTML += i < count ? '★' : '☆';
        }
        return starsHTML;
    };

    // ล้างข้อมูลเก่า (ถ้ามี)
    reviewsContainer.innerHTML = '';

    // วนลูปเพื่อสร้างรีวิวแต่ละอัน
    reviewsData.forEach(review => {
        const reviewElement = document.createElement('div');
        reviewElement.className = 'review-item';

        const reviewHTML = `
            <img class="review-avatar" src="${review.avatar}" alt="Avatar of ${review.name}">
            <div class="review-content">
                <div class="review-bubble">
                    <strong class="review-name">${review.name}</strong>
                    <p class="review-text">${review.text}</p>
                </div>
                <div class="review-meta">
                    <span class="review-timestamp">${review.timestamp}</span>
                    <span class="review-stars">${createStars(review.stars)}</span>
                </div>
            </div>
        `;
        
        reviewElement.innerHTML = reviewHTML;
        reviewsContainer.appendChild(reviewElement);
    });
});
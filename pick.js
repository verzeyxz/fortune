document.addEventListener('DOMContentLoaded', () => {
    // --- Constants & Configuration ---
    const DECK_LAYOUT_CONFIG = {
        desktop: { cardsPerRow: 19, cardWidth: 90, overlapX: 40, rowHeight: 130 },
        mobile: { cardsPerRow: 13, cardWidthRatio: 7, overlapRatio: 0.35, rowHeightRatio: 0.75 } // cardWidth will be containerWidth / 7, rowHeight will be cardHeight * 0.75
    };

    // --- DOM Elements ---
    // Use optional chaining (?) for robustness in case an element doesn't exist on a particular page.
    const selectionScreen = document.getElementById('selection-screen');
    const resultsScreen = document.getElementById('results-screen');
    const resultsGrid = document.getElementById('results-grid');
    const resultControls = document.getElementById('result-controls');
    const cardGrid = document.getElementById('card-grid');
    const counterDiv = document.getElementById('counter');
    const confirmButton = document.getElementById('confirm-button');
    const selectionTray = document.getElementById('selection-tray');
    const pageTitle = document.getElementById('page-title');
    const resultTitle = document.getElementById('result-title');
    const shuffleButton = document.getElementById('shuffle-button');
    const cardModalContainer = document.getElementById('card-modal-container');
    const modalCloseBtn = document.getElementById('modal-close-btn');
    const modalCardImg = document.getElementById('modal-card-img');
    const modalCardName = document.getElementById('modal-card-name');

    // --- State Variables ---
    let selectedCards = [];
    let maxSelections = 10;
    let shuffledDeck = [];
    let renderTimeout;

    // --- CORE LOGIC: Initialize page based on URL parameter ---
    /**
     * Initializes the page based on URL parameters.
     * It can either set up the card selection mode or a history view mode.
     */
    function initializePage() {
        const urlParams = new URLSearchParams(window.location.search);
        const cardsFromUrl = urlParams.get('cards');
        const countFromUrl = parseInt(urlParams.get('count'), 10);

        if (cardsFromUrl) {
            // MODE 1: HISTORY VIEW (e.g., from a saved reading link)
            const cardIds = cardsFromUrl.split(',').filter(id => id); // Filter out empty strings
            if (cardIds.length > 0) {
                selectedCards = cardIds;
                maxSelections = cardIds.length;
                selectionScreen?.classList.add('hidden');
                resultsScreen?.classList.remove('hidden');
                if (resultTitle) resultTitle.textContent = `ผลคำทำนายย้อนหลัง`;
                renderResults(true); // true = isHistoryView
            } else {
                // Fallback to selection mode if 'cards' param is empty
                setupSelectionMode(countFromUrl);
            }
        } else {
            // MODE 2: SELECTION VIEW
            setupSelectionMode(countFromUrl);
        }
    }

    /**
     * Sets up the page for the user to select cards.
     * @param {number|NaN} countFromUrl - The number of cards to select, from the URL.
     */
    function setupSelectionMode(countFromUrl) {
        const validCounts = [1, 2, 3, 4, 10];
        if (countFromUrl && validCounts.includes(countFromUrl)) {
            maxSelections = countFromUrl;
        }
        
        if (pageTitle) pageTitle.textContent = `เลือกไพ่ ${maxSelections} ใบ`;
        if (resultTitle) resultTitle.textContent = `ไพ่ทั้ง ${maxSelections} ใบของคุณ`;
        
        shuffleAndRender();
        updateUI();
    }

    // --- RENDER & LAYOUT FUNCTIONS ---

    /**
     * Shuffles the tarot deck and triggers a re-render.
     */
    function shuffleAndRender() {
        shuffledDeck = shuffle([...tarotDeck]);
        renderDeck();
    }
    // --- [REPLACED] RENDER DECK FUNCTION FOR RESPONSIVE STAGGERED LAYOUT ---
    function renderDeck() {
        cardGrid.innerHTML = ''; 
        const shuffledDeck = shuffle([...tarotDeck]);
        const containerWidth = cardGrid.offsetWidth;

        // --- ค่าที่ปรับได้สำหรับ Layout ---
        const cardsPerRow = 19;
        let cardWidth, overlapX, rowHeight;

        // --- กำหนดค่าตามขนาดหน้าจอ ---
        if (containerWidth < 768) { // Mobile
            // บนมือถือ ทำให้การ์ดมีขนาดเล็กลงและซ้อนกันมากขึ้นเพื่อให้พอดี
            cardWidth = containerWidth / 8; // ขนาดการ์ดเป็นสัดส่วนของความกว้างจอ
            overlapX = cardWidth * 0.4; // ซ้อนกัน 60% ของความกว้างการ์ด
            rowHeight = cardWidth * (3/2) * 0.6; // ลดความสูงแถวให้ชิดกันขึ้น
        } else { // Desktop
            cardWidth = 90;
            overlapX = 40;
            rowHeight = 150;
        }

        // คำนวณความกว้างทั้งหมดของแถวไพ่เพื่อจัดให้อยู่กึ่งกลาง
        const totalRowWidth = (cardsPerRow - 1) * overlapX + cardWidth;
        const startOffset = (containerWidth - totalRowWidth) / 2;

        shuffledDeck.forEach((card, index) => {
            const cardContainer = document.createElement('div');
            cardContainer.className = 'card-container';
            cardContainer.dataset.id = card.id;

            // คำนวณแถวและคอลัมน์
            const row = Math.floor(index / cardsPerRow);
            const col = index % cardsPerRow;

            // คำนวณตำแหน่ง
            const top = row * rowHeight;
            const left = startOffset + (col * overlapX);
            
            // กำหนดสไตล์จาก JavaScript
            cardContainer.style.width = `${cardWidth}px`;
            cardContainer.style.top = `${top}px`;
            cardContainer.style.left = `${left}px`;
            cardContainer.style.zIndex = col;
            cardContainer.style.animationDelay = `${index * 20}ms`;

            const cardBack = document.createElement('div');
            cardBack.className = 'card-back';
            cardContainer.appendChild(cardBack);

            cardContainer.addEventListener('click', () => toggleSelection(card.id));
            
            cardGrid.appendChild(cardContainer);
        });
    }




function renderResults(isHistoryView = false) {
        if (!resultsGrid || !resultControls) return;
        resultsGrid.innerHTML = '';
        resultControls.innerHTML = '';
        if (selectedCards.length <= 5) {
            resultsGrid.style.gridTemplateColumns = `repeat(${selectedCards.length}, 1fr)`;
        } else {
            resultsGrid.style.gridTemplateColumns = 'repeat(5, 1fr)';
            resultsGrid.style.gridTemplateRows = 'repeat(2, auto)';
        }
        selectedCards.forEach((cardId, index) => {
            const cardData = tarotDeck.find(c => c.id === cardId);
            if (cardData) {
                const resultCard = document.createElement('div');
                resultCard.className = 'result-card';
                resultCard.style.animationDelay = `${index * 100}ms`;
                resultCard.innerHTML = `<img src="${cardData.img}" alt="${cardData.name}"><p>${cardData.name}</p>`;
                resultCard.addEventListener('click', () => openCardModal(cardId));
                resultsGrid.appendChild(resultCard);
            }
        });
        
        if (isHistoryView) {
            const backToProfileBtn = document.createElement('a');
            backToProfileBtn.href = 'profile.php';
            backToProfileBtn.className = 'confirm-btn';
            backToProfileBtn.textContent = 'กลับสู่หน้าโปรไฟล์';
            resultControls.appendChild(backToProfileBtn);
        } else {
            // สร้างปุ่มแบบไดนามิก
            // IS_LOGGED_IN ถูกส่งมาจาก pick.php
            if (typeof IS_LOGGED_IN !== 'undefined' && IS_LOGGED_IN) {
                const saveReadingBtn = document.createElement('button');
                saveReadingBtn.id = 'save-reading-btn';
                saveReadingBtn.className = 'save-reading-btn';
                saveReadingBtn.textContent = 'บันทึกผลคำทำนาย';
                saveReadingBtn.addEventListener('click', handleSaveReading);
                resultControls.appendChild(saveReadingBtn);
            }
            const saveImageBtn = document.createElement('button');
            saveImageBtn.id = 'save-image-btn';
            saveImageBtn.className = 'save-btn';
            saveImageBtn.textContent = 'บันทึกเป็นรูปภาพ';
            saveImageBtn.addEventListener('click', saveResultsAsImage);
            resultControls.appendChild(saveImageBtn);
            const resetButton = document.createElement('button');
            resetButton.id = 'reset-button';
            resetButton.className = 'confirm-btn';
            resetButton.textContent = 'เลือกใหม่อีกครั้ง';
            resetButton.addEventListener('click', () => { window.location.href = `pick.php?count=${maxSelections}`; });
            resultControls.appendChild(resetButton);
        }
    }

    // --- UI & STATE LOGIC ---

    /**
     * Toggles the selection state of a card.
     * @param {string} cardId - The ID of the card to select/deselect.
     */
    function toggleSelection(cardId) {
        const cardInGrid = document.querySelector(`.card-container[data-id="${cardId}"]`);
        const cardIndex = selectedCards.indexOf(cardId);

        if (cardIndex > -1) {
            // Card is already selected, so deselect it
            selectedCards.splice(cardIndex, 1);
            cardInGrid?.classList.remove('selected');
        } else {
            // Card is not selected, so select it if not at max
            if (selectedCards.length < maxSelections) {
                selectedCards.push(cardId);
                cardInGrid?.classList.add('selected');
            } else {
                // Use a library like SweetAlert2 for better user feedback
                Swal.fire('เลือกครบแล้ว', `คุณเลือกไพ่ครบ ${maxSelections} ใบแล้ว`, 'info');
            }
        }
        updateUI();
    }
    
    /**
     * Updates the UI elements based on the current state (counter, button, tray).
     */
    function updateUI() {
        if (counterDiv) {
            counterDiv.textContent = `เลือกแล้ว ${selectedCards.length}/${maxSelections} ใบ`;
        }
        
        if (confirmButton) {
            const isReady = selectedCards.length === maxSelections;
            confirmButton.disabled = !isReady;
            confirmButton.classList.toggle('ready', isReady);
        }

        if (selectionTray) {
            selectionTray.innerHTML = '';
            const fragment = document.createDocumentFragment();
            selectedCards.forEach((cardId, index) => {
                const trayCard = document.createElement('div');
                trayCard.className = 'tray-card';
                trayCard.dataset.id = cardId;
                trayCard.style.left = `${index * 35}px`;
                trayCard.style.zIndex = index;
                
                // Add event listener to remove card from tray
                trayCard.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent grid click event
                    toggleSelection(cardId);
                });
                
                fragment.appendChild(trayCard);
                // Stagger the animation for a nice effect
                setTimeout(() => trayCard.classList.add('in-tray'), 10 * index);
            });
            selectionTray.appendChild(fragment);
        }
    }

    // --- MODAL FUNCTIONS ---

    /**
     * Opens the modal to show card details.
     * @param {string} cardId - The ID of the card to display.
     */
    function openCardModal(cardId) {
        const cardData = tarotDeck.find(c => c.id === cardId);
        if (!cardData || !cardModalContainer) return;

        if (modalCardImg) modalCardImg.src = cardData.img;
        if (modalCardName) modalCardName.textContent = cardData.name;
        
        cardModalContainer.classList.add('visible');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    /**
     * Closes the card detail modal.
     */
    function closeCardModal() {
        if (!cardModalContainer) return;
        cardModalContainer.classList.remove('visible');
        document.body.style.overflow = ''; // Restore scrolling
    }

    // --- ACTION HANDLERS ---

    /**
     * Handles the shuffle button click with a visual transition.
     */
    async function handleShuffleClick() {
        if (!shuffleButton || !cardGrid) return;

        shuffleButton.disabled = true;
        selectedCards = [];
        updateUI();

        cardGrid.style.transition = 'opacity 0.3s ease-out';
        cardGrid.style.opacity = '0';

        // Wait for fade out animation to complete
        await new Promise(resolve => setTimeout(resolve, 300));
        
        shuffleAndRender(); 

        cardGrid.style.opacity = '1';
        shuffleButton.disabled = false;
    }

    /**
     * Resets the selection by reloading the page with the current count.
     */
    function handleReset() {
        window.location.href = `pick.php?count=${maxSelections}`;
    }

    /**
     * Saves the current reading results as a PNG image.
     * Requires html2canvas and SweetAlert2 libraries.
     */
    async function saveResultsAsImage() {
        const elementToCapture = document.getElementById('results-screen');
        if (!elementToCapture) {
            console.error("Result screen element not found.");
            return;
        }

        Swal.fire({
            title: 'กำลังสร้างรูปภาพ...',
            text: 'โปรดรอสักครู่',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const canvas = await html2canvas(elementToCapture, {
                useCORS: true,
                backgroundColor: null, // Use the element's background
                scale: 2 // Higher scale for better resolution
            });
            const imageDataUrl = canvas.toDataURL('image/png');
            Swal.close();

            const result = await Swal.fire({
                title: 'สร้างรูปภาพสำเร็จ!',
                text: 'คลิก "บันทึก" เพื่อดาวน์โหลดรูปภาพของคุณ',
                imageUrl: imageDataUrl,
                imageAlt: 'ผลการเลือกไพ่',
                imageHeight: 200,
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'บันทึกรูปภาพ',
                cancelButtonText: 'ยกเลิก'
            });

            if (result.isConfirmed) {
                const link = document.createElement('a');
                link.href = imageDataUrl;
                link.download = `ผลคำทำนาย-ดูดวงกับเรฟ-${Date.now()}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        } catch (error) {
            console.error('Error generating image:', error);
            Swal.fire({ 
                icon: 'error', 
                title: 'เกิดข้อผิดพลาด', 
                text: 'ไม่สามารถสร้างรูปภาพได้ โปรดลองอีกครั้ง' 
            });
        }
    }

    /**
     * Placeholder for saving the reading to a user's profile.
     * This would typically involve an AJAX/fetch call to a backend endpoint.
     */
       async function handleSaveReading() {
        const { value: readingTitle } = await Swal.fire({
            title: 'บันทึกผลคำทำนาย',
            input: 'text',
            inputLabel: 'ตั้งชื่อหรือใส่คำถามสำหรับการเปิดไพ่ครั้งนี้',
            inputPlaceholder: 'เช่น "เรื่องงานที่ใหม่" หรือ "ความสัมพันธ์ตอนนี้"',
            showCancelButton: true,
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก',
            background: '#1a1a1a', // สีพื้นหลังของ popup
            color: '#fff',      // สีตัวอักษร
            inputValidator: (value) => {
                if (!value) {
                    return 'กรุณาตั้งชื่อการทำนาย!';
                }
            }
        });

        if (readingTitle) {
            const data = {
                title: readingTitle,
                cards: selectedCards
            };

            Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

            try {
                const response = await fetch('admin/api/readings.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire('สำเร็จ!', 'บันทึกผลคำทำนายของคุณเรียบร้อยแล้ว สามารถดูได้ที่หน้าโปรไฟล์', 'success');
                } else {
                    throw new Error(result.message || 'ไม่สามารถบันทึกได้');
                }
            } catch (error) {
                console.error('Error saving reading:', error);
                Swal.fire('ผิดพลาด!', 'ไม่สามารถเชื่อมต่อเพื่อบันทึกข้อมูลได้', 'error');
            }
        }
    }

    // --- UTILITY FUNCTIONS ---
    function shuffle(array) {
        let currentIndex = array.length, randomIndex;
        while (currentIndex !== 0) {
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex--;
            [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]];
        }
        return array;
    }

    // --- EVENT LISTENERS ---
    function setupEventListeners() {
        shuffleButton?.addEventListener('click', handleShuffleClick);
        confirmButton?.addEventListener('click', () => {
            if (selectedCards.length === maxSelections) {
                selectionScreen?.classList.add('hidden');
                resultsScreen?.classList.remove('hidden');
                renderResults(false);
            }
        });
        modalCloseBtn?.addEventListener('click', closeCardModal);
        cardModalContainer?.addEventListener('click', (event) => { if (event.target === cardModalContainer) closeCardModal(); });
        document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && cardModalContainer?.classList.contains('visible')) closeCardModal(); });
        window.addEventListener('resize', () => {
            clearTimeout(renderTimeout);
            renderTimeout = setTimeout(renderDeck, 250);
        });
    }

    // --- INITIALIZATION ---
    initializePage();
    setupEventListeners();
});
    document.addEventListener('DOMContentLoaded', () => {
    // --- Constants & Configuration ---
    // !!! SECURITY WARNING !!!
    // For development/testing ONLY. Do NOT expose this key in a production environment.
    // Your API key should be stored securely on a server and fetched by the client.
    // Hardcoding it here makes it publicly accessible and can lead to misuse and unexpected charges.
    const GEMINI_API_KEY = 'AIzaSyCen3mQP8GUgFG6n1wg5v1n9pkTpKMOGto'; // <-- Replace with your key for testing

    const DECK_LAYOUT_CONFIG = {
        desktop: { cardsPerRow: 19, cardWidth: 90, overlapX: 40, rowHeight: 130, cardHeight: 135 },
        mobile: { cardsPerRow: 13, cardWidthRatio: 8, overlapRatio: 0.4, rowHeightRatio: 0.6, cardHeightRatio: 1.5 }
    };

    // --- DOM Elements ---
    // Use optional chaining (?) for robustness in case an element doesn't exist on a particular page.
    const selectionScreen = document.getElementById('selection-screen');
    const resultsScreen = document.getElementById('results-screen');
    const resultsGrid = document.getElementById('results-grid');
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
        shuffledDeck = shuffle([...tarotDeck]); // Keep the shuffled deck in state
        renderDeck(shuffledDeck);
    }

    /**
     * Renders the deck of cards in a staggered, responsive layout.
     * @param {Array} deckToRender - The array of card objects to render.
     */
    function renderDeck(deckToRender) {
        if (!cardGrid) return;
        cardGrid.innerHTML = '';
        const containerWidth = cardGrid.offsetWidth;
        const isMobile = containerWidth < 768;
        const config = isMobile ? DECK_LAYOUT_CONFIG.mobile : DECK_LAYOUT_CONFIG.desktop;

        // Calculate dimensions based on config
        const cardsPerRow = config.cardsPerRow;
        const cardWidth = isMobile ? containerWidth / config.cardWidthRatio : config.cardWidth;
        const cardHeight = isMobile ? cardWidth * config.cardHeightRatio : config.cardHeight;
        const overlapX = isMobile ? cardWidth * config.overlapRatio : config.overlapX;
        const rowHeight = isMobile ? cardHeight * config.rowHeightRatio : config.rowHeight;

        // Center the whole block of cards
        const totalRowWidth = (cardsPerRow - 1) * overlapX + cardWidth;
        const startOffset = (containerWidth - totalRowWidth) / 2;

        const fragment = document.createDocumentFragment();
        deckToRender.forEach((card, index) => {
            const cardContainer = document.createElement('div');
            cardContainer.className = 'card-container';
            cardContainer.dataset.id = card.id;

            const row = Math.floor(index / cardsPerRow);
            const col = index % cardsPerRow;

            cardContainer.style.width = `${cardWidth}px`;
            cardContainer.style.top = `${row * rowHeight}px`;
            cardContainer.style.left = `${startOffset + (col * overlapX)}px`;
            cardContainer.style.zIndex = col;
            cardContainer.style.animationDelay = `${index * 20}ms`;

            cardContainer.innerHTML = '<div class="card-back"></div>';
            cardContainer.addEventListener('click', () => toggleSelection(card.id));
            fragment.appendChild(cardContainer);
        });
        cardGrid.appendChild(fragment);
    }



 function renderResults(isHistoryView = false) {
        if (!resultsGrid) return;
        resultsGrid.innerHTML = '';

        // ตั้งค่า layout ของ grid
        const gridCols = selectedCards.length <= 5 ? selectedCards.length : 5;
        resultsGrid.style.gridTemplateColumns = `repeat(${gridCols}, 1fr)`;

        // สร้าง Element ของไพ่ทั้งหมด แต่ยังซ่อนไว้
        selectedCards.forEach((cardId) => {
            const cardData = tarotDeck.find(c => c.id === cardId);
            if (cardData) {
                const cardWrapper = document.createElement('div');
                cardWrapper.className = 'result-card'; // Wrapper for animation
                
                cardWrapper.innerHTML = `
                    <div class="result-card-flipper" data-id="${cardId}">
                        <div class="result-card-inner">
                            <div class="result-card-face result-card-back"></div>
                            <div class="result-card-face result-card-front" style="background-image: url('${cardData.img}')"></div>
                        </div>
                    </div>
                    <p class="result-card-name">${cardData.name}</p>
                `;
                resultsGrid.appendChild(cardWrapper);
            }
        });


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

 function typewriterEffect(element, text, callback) {
        let i = 0;
        let speed = 30;
        element.innerHTML = '';
        element.style.borderRight = '3px solid rgba(255, 255, 255, .75)';
        function type() {
            if (i < text.length) {
                element.innerHTML += text.charAt(i);
                const char = text.charAt(i);
                if (char === ' ' || char === '\n' || char === '።') {
                    speed = 30 + Math.random() * 50;
                } else {
                    speed = 20 + Math.random() * 20;
                }
                i++;
                setTimeout(type, speed);
            } else {
                element.classList.add('typing-done');
                if (callback) callback();
            }
        }
        type();
    }

    async function handleAiInterpretation() {
        const { value: userQuestion } = await Swal.fire({
            title: 'คำถามถึง Oracle',
            input: 'text',
            inputLabel: 'โปรดระบุเรื่องที่คุณต้องการถามจากไพ่ชุดนี้',
            inputPlaceholder: 'เช่น ความรัก, การงาน, การตัดสินใจ...',
            showCancelButton: true,
            confirmButtonText: 'ส่งคำถามและตีความไพ่',
            cancelButtonText: 'ยกเลิก',
            customClass: { popup: 'oracle-theme', title: 'oracle-theme', inputLabel: 'oracle-theme', input: 'swal2-input'},
            inputValidator: (value) => {
                if (!value) { return 'กรุณาใส่คำถามหรือหัวข้อที่ต้องการทราบ!'; }
            }
        });

        if (userQuestion) {
            Swal.fire({
                title: 'Oracle กำลังมองลึกเข้าไปในกระแสแห่งโชคชะตา...',
                html: '<div class="loader"></div>',
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: { popup: 'oracle-theme', title: 'oracle-theme' },
            });

            // The API key is now sourced from the constant at the top of the file.
            const api_url = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=${GEMINI_API_KEY}`;

            const cardNames = selectedCards.map(id => tarotDeck.find(c => c.id === id)?.name).filter(Boolean);
            if (cardNames.length === 0) return; // Don't call API if no cards are selected
            const card_list_string = cardNames.join(', ');
            const prompt_message = `ในบทบาทของ 'Oracle Ref' นักพยากรณ์ไพ่ยิปซีผู้มีญาณวิเศษ จงตีความไพ่ที่ผู้ใช้เปิดได้: ${card_list_string} สำหรับคำถามที่ว่า: '${userQuestion}'. ให้เรียบเรียงเป็นบทสรุปที่กระชับ ได้ใจความ และตรงประเด็น ไม่ต้องอารัมภบท เน้นการเล่าเรื่องที่เชื่อมโยงกัน และจบด้วยคำแนะนำที่นำไปใช้ได้จริงเพียง 1-2 ประโยค ตอบเป็นภาษาไทยที่งดงามแต่เข้าใจง่าย`;

            const api_data = {
                contents: [{ parts: [{ text: prompt_message }] }],
                generationConfig: { temperature: 0.7, maxOutputTokens: 8192 },
                safetySettings: [
                    { category: 'HARM_CATEGORY_HARASSMENT', threshold: 'BLOCK_MEDIUM_AND_ABOVE' },
                    { category: 'HARM_CATEGORY_HATE_SPEECH', threshold: 'BLOCK_MEDIUM_AND_ABOVE' },
                    { category: 'HARM_CATEGORY_SEXUALLY_EXPLICIT', threshold: 'BLOCK_MEDIUM_AND_ABOVE' },
                    { category: 'HARM_CATEGORY_DANGEROUS_CONTENT', threshold: 'BLOCK_MEDIUM_AND_ABOVE' }
                ]
            };

            try {
                const response = await fetch(api_url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(api_data)
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(`Server responded with status ${response.status}: ${errorData.error?.message || 'Unknown error'}`);
                }

                const result = await response.json();

                if (result.candidates && result.candidates[0].content.parts[0].text) {
                    const interpretation = result.candidates[0].content.parts[0].text;
                    Swal.fire({
                        title: 'คำทำนายจาก Oracle Ref',
                        html: '<div id="ai-interpretation-text"></div>',
                        confirmButtonText: 'ขอบคุณสำหรับคำแนะนำ',
                        customClass: { popup: 'oracle-theme', title: 'oracle-theme', htmlContainer: 'oracle-theme' },
                        didOpen: () => {
                            const textArea = document.getElementById('ai-interpretation-text');
                            typewriterEffect(textArea, interpretation);
                        }
                    });
                } else {
                    throw new Error('AI ไม่สามารถสร้างคำทำนายได้ อาจเนื่องมาจากข้อจำกัดด้านความปลอดภัย');
                }
            } catch (error) {
                console.error("AI interpretation error:", error);
                Swal.fire('เกิดข้อผิดพลาด', `ไม่สามารถเชื่อมต่อกับ AI Oracle ได้: ${error.message}`, 'error');
            }
        }
    }

    if (shuffleButton) shuffleButton.addEventListener('click', handleShuffleClick);
    if (confirmButton) confirmButton.addEventListener('click', () => {  
        if (selectedCards.length === maxSelections) {
            selectionScreen?.classList.add('hidden');
            resultsScreen?.classList.remove('hidden');
            renderResults(false);
        }
    });
    if (cardGrid) {
        cardGrid.addEventListener('click', (event) => {
            const cardContainer = event.target.closest('.card-container');
            if (cardContainer) {
                const cardId = cardContainer.dataset.id;
                if (cardId) openCardModal(cardId);
            }
        });
    }
    if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeCardModal);
    if (cardModalContainer) {
        cardModalContainer.addEventListener('click', (event) => {
            if (event.target === cardModalContainer) closeCardModal();
        });
    }
    }
    // --- Event Listeners for buttons ---
    if (shuffleButton) shuffleButton.addEventListener('click', handleShuffleClick);
   if (confirmButton) confirmButton.addEventListener('click', () => {
       if (selectedCards.length === maxSelections) {
           selectionScreen?.classList.add('hidden');
           resultsScreen?.classList.remove('hidden');
           renderResults(false);
       }
   });
    if (cardGrid) {
        cardGrid.addEventListener('click', (event) => {
            const cardContainer = event.target.closest('.card-container');
            if (cardContainer) {
                const cardId = cardContainer.dataset.id;
                if (cardId) openCardModal(cardId);
            }
        });
    }
    if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeCardModal);
    if (cardModalContainer) {
        cardModalContainer.addEventListener('click', (event) => {
            if (event.target === cardModalContainer) closeCardModal();
        });
    }

    // Initialize the page
    initializePage();
});

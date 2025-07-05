
// Fungsi untuk menghitung carbon (contoh sederhana)
function calculateHomeActivityCarbon(details) {
    const duration = parseInt(details['activity-duration']) || 0;
    return Math.round(duration * 0.5); // Contoh perhitungan
}

function calculateTransportCarbon(details) {
    const distance = parseInt(details['round-trip']) || 0;
    const vehicle = details['vehicle-type'];
    
    // Contoh faktor emisi per km
    const factors = {
        'walking': 0,
        'bike': 0,
        'motorcycle': 0.1,
        'car': 0.2,
        'bus': 0.05,
        'train': 0.03
    };
    
    return Math.round(distance * (factors[vehicle] || 0.15));
}

// Di dalam home3.js, tambahkan fungsi ini
function loadAndDisplayTodoLists() {
    const savedTasks = JSON.parse(localStorage.getItem('savedTasks')) || [];
    const taskListContainer = document.querySelector('.task-list-container');
    
    if (!taskListContainer) return;
    
    // Kelompokkan task berdasarkan judul (todolist)
    const groupedTasks = {};
    savedTasks.forEach(task => {
        const title = task.details.todolist || 'Untitled Task';
        if (!groupedTasks[title]) {
            groupedTasks[title] = {
                title: title,
                tasks: [],
                totalCarbon: 0
            };
        }
        const carbon = calculateTaskCarbon(task);
        groupedTasks[title].tasks.push(task);
        groupedTasks[title].totalCarbon += carbon;
    });
    
    // Kosongkan container
    taskListContainer.innerHTML = '<h2 class="task-list-title">Your To-Do Lists</h2>';
    
    // Buat section untuk setiap grup
    Object.values(groupedTasks).forEach(group => {
        const section = document.createElement('div');
        section.className = 'task-section';
        
        section.innerHTML = `
            <div class="section-header">
                <h3>${group.title}</h3>
                <span class="carbon-label">Total Carbon : ${group.totalCarbon} kg CO₂</span>
            </div>
            <ul class="task-items">
                ${group.tasks.map(task => `
                    <li class="task-item" data-task-id="${task.timestamp}">
                        <span>${getTaskDescription(task)}</span>
                        <span class="task-carbon">${calculateTaskCarbon(task)} kg CO₂</span>
                        <button class="remove-task-btn">Remove</button>
                    </li>
                `).join('')}
            </ul>
        `;
        
        taskListContainer.appendChild(section);
    });

    // Tambahkan event listener untuk remove button
    taskListContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-task-btn')) {
            const taskElement = e.target.closest('.task-item');
            const taskId = taskElement.dataset.taskId;
            removeTask(taskId);
        }
    });
}

// Tambahkan fungsi perhitungan untuk online activity
function calculateOnlineActivityCarbon(details) {
    const duration = parseInt(details['online-duration']) || 0;
    const device = details['online-device'];
    
    // Contoh faktor emisi per menit berdasarkan device
    const factors = {
        'phone': 0.01,
        'laptop': 0.02,
        'tablet': 0.015,
        'pc': 0.03,
        'other': 0.02
    };
    
    return Math.round(duration * (factors[device] || 0.02));
}

// Tambahkan fungsi perhitungan untuk shopping
function calculateShoppingCarbon(details) {
    const weight = parseInt(details['item-weight']) || 0;
    const method = details['shopping-method'];
    const isFood = details['stuff-type'] === 'food';
    const foodType = details['food-type'];
    
    // Contoh faktor emisi per gram
    let factor = 0.001; // default
    
    if (method.includes('online-express')) factor = 0.002;
    else if (method.includes('online-normal')) factor = 0.0015;
    else if (method.includes('manual-transport')) factor = 0.0012;
    
    // Tambahan untuk makanan tertentu
    if (isFood) {
        if (foodType === 'beef') factor += 0.005;
        else if (foodType === 'chicken') factor += 0.002;
    }
    
    return Math.round(weight * factor);
}

// Fungsi helper untuk menghitung carbon per task
function calculateTaskCarbon(task) {
    switch(task.type) {
        case 'home-activity':
            return calculateHomeActivityCarbon(task.details);
        case 'online-activity':
            return calculateOnlineActivityCarbon(task.details);
        case 'going-out':
            return calculateTransportCarbon(task.details);
        case 'buy-stuff':
            return calculateShoppingCarbon(task.details);
        default:
            return 0;
    }
}

// Update the saveTaskToStorage function (add this if it doesn't exist)
function saveTaskToStorage(taskData) {
    // Get existing tasks or initialize empty array
    const savedTasks = JSON.parse(localStorage.getItem('savedTasks')) || [];
    
    // Add new task
    savedTasks.push(taskData);
    
    // Save back to localStorage
    localStorage.setItem('savedTasks', JSON.stringify(savedTasks));
    
    return true;
}

// Pindahkan ini ke luar DOMContentLoaded
function removeTask(taskId) {
    const savedTasks = JSON.parse(localStorage.getItem('savedTasks')) || [];
    const updatedTasks = savedTasks.filter(task => task.timestamp !== taskId);
    localStorage.setItem('savedTasks', JSON.stringify(updatedTasks));
    loadAndDisplayTasks();
    loadAndDisplayTodoLists();
}
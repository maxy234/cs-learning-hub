// Main JavaScript file for CS Learning Hub

// DOM Elements
const darkModeToggle = document.getElementById('darkModeToggle');
const navbar = document.querySelector('.navbar');
const filterBtns = document.querySelectorAll('.filter-btn');
const newsletterForm = document.getElementById('newsletterForm');

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    initializeAnimations();
    checkUserSession();
    loadDynamicContent();
    setupEventListeners();
});

// Animation Initialization
function initializeAnimations() {
    // Add fade-in animation to elements as they scroll into view
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.feature-card, .subject-card, .blog-card, .internship-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
}

// Check User Session
function checkUserSession() {
    fetch('/api/check-session.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                updateUIForLoggedInUser(data.user);
            }
        })
        .catch(error => console.error('Session check failed:', error));
}

// Update UI for logged in user
function updateUIForLoggedInUser(user) {
    const authButtons = document.querySelector('.auth-buttons');
    if (authButtons) {
        authButtons.innerHTML = `
            <span class="user-greeting">Hello, ${user.username}</span>
            <a href="/dashboard.php" class="btn btn-primary">Dashboard</a>
            <a href="/logout.php" class="btn btn-secondary">Logout</a>
        `;
    }
}

// Setup Event Listeners
function setupEventListeners() {
    // Dark Mode Toggle
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', toggleDarkMode);
    }

    // Navbar Scroll Effect
    window.addEventListener('scroll', handleNavbarScroll);

    // Filter Buttons
    filterBtns.forEach(btn => {
        btn.addEventListener('click', handleFilterClick);
    });

    // Newsletter Form
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', handleNewsletterSubmit);
    }

    // Search Functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(handleSearch, 300));
    }

    // Smooth Scroll for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', handleSmoothScroll);
    });
}

// Dark Mode Toggle
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
    
    // Update toggle icon
    const icon = darkModeToggle.querySelector('i');
    if (icon) {
        icon.className = isDarkMode ? 'fas fa-sun' : 'fas fa-moon';
    }
}

// Navbar Scroll Effect
function handleNavbarScroll() {
    if (window.scrollY > 100) {
        navbar.classList.add('navbar-scrolled');
    } else {
        navbar.classList.remove('navbar-scrolled');
    }
}

// Filter Click Handler
function handleFilterClick(e) {
    // Remove active class from all filters
    filterBtns.forEach(btn => btn.classList.remove('active'));
    
    // Add active class to clicked filter
    e.target.classList.add('active');
    
    // Get filter value
    const filterValue = e.target.dataset.filter;
    
    // Filter items
    filterItems(filterValue);
}

// Filter Items
function filterItems(filter) {
    const items = document.querySelectorAll('.filter-item');
    
    items.forEach(item => {
        if (filter === 'all' || item.dataset.category === filter) {
            item.style.display = 'block';
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'scale(1)';
            }, 50);
        } else {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.8)';
            setTimeout(() => {
                item.style.display = 'none';
            }, 300);
        }
    });
}

// Newsletter Form Submit
function handleNewsletterSubmit(e) {
    e.preventDefault();
    
    const email = document.getElementById('newsletterEmail').value;
    const formData = new FormData();
    formData.append('email', email);
    
    fetch('/api/subscribe-newsletter.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Successfully subscribed to newsletter!', 'success');
            e.target.reset();
        } else {
            showNotification(data.message || 'Subscription failed', 'error');
        }
    })
    .catch(error => {
        console.error('Newsletter subscription failed:', error);
        showNotification('An error occurred. Please try again.', 'error');
    });
}

// Search Handler
function handleSearch(e) {
    const searchTerm = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.searchable-item');
    
    items.forEach(item => {
        const title = item.dataset.title.toLowerCase();
        const description = item.dataset.description.toLowerCase();
        
        if (title.includes(searchTerm) || description.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Smooth Scroll
function handleSmoothScroll(e) {
    e.preventDefault();
    const targetId = this.getAttribute('href');
    const targetElement = document.querySelector(targetId);
    
    if (targetElement) {
        targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Show Notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Debounce Function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Load Dynamic Content
function loadDynamicContent() {
    // Load latest blog posts
    loadLatestBlogPosts();
    
    // Load upcoming contests
    loadUpcomingContests();
    
    // Load internship alerts
    loadInternshipAlerts();
    
    // Load testimonials
    loadTestimonials();
}

// Load Latest Blog Posts
function loadLatestBlogPosts() {
    fetch('/api/latest-posts.php')
        .then(response => response.json())
        .then(posts => {
            const container = document.getElementById('latestPosts');
            if (container) {
                container.innerHTML = posts.map(post => createBlogPostHTML(post)).join('');
            }
        })
        .catch(error => console.error('Failed to load blog posts:', error));
}

// Load Upcoming Contests
function loadUpcomingContests() {
    fetch('/api/upcoming-contests.php')
        .then(response => response.json())
        .then(contests => {
            const container = document.getElementById('upcomingContests');
            if (container) {
                container.innerHTML = contests.map(contest => createContestHTML(contest)).join('');
            }
        })
        .catch(error => console.error('Failed to load contests:', error));
}

// Load Internship Alerts
function loadInternshipAlerts() {
    fetch('/api/internship-alerts.php')
        .then(response => response.json())
        .then(internships => {
            const container = document.getElementById('internshipAlerts');
            if (container) {
                container.innerHTML = internships.map(internship => createInternshipHTML(internship)).join('');
            }
        })
        .catch(error => console.error('Failed to load internships:', error));
}

// Load Testimonials
function loadTestimonials() {
    fetch('/api/testimonials.php')
        .then(response => response.json())
        .then(testimonials => {
            const container = document.getElementById('testimonials');
            if (container) {
                container.innerHTML = testimonials.map(testimonial => createTestimonialHTML(testimonial)).join('');
            }
        })
        .catch(error => console.error('Failed to load testimonials:', error));
}

// HTML Generators
function createBlogPostHTML(post) {
    return `
        <div class="blog-card" data-post-id="${post.id}">
            <img src="${post.image}" alt="${post.title}" class="blog-image">
            <div class="blog-content">
                <div class="blog-meta">
                    <span>${post.date}</span>
                    <span>${post.author}</span>
                </div>
                <h3 class="blog-title">${post.title}</h3>
                <p class="blog-excerpt">${post.excerpt}</p>
                <a href="/blog/post.php?id=${post.id}" class="read-more">Read More →</a>
            </div>
        </div>
    `;
}

function createContestHTML(contest) {
    return `
        <div class="update-card">
            <h4>${contest.name}</h4>
            <p>${contest.date} • ${contest.platform}</p>
            <a href="${contest.link}" target="_blank" class="btn btn-small">Join Contest</a>
        </div>
    `;
}

function createInternshipHTML(internship) {
    return `
        <div class="update-card">
            <h4>${internship.company}</h4>
            <p>${internship.position}</p>
            <p>📍 ${internship.location}</p>
            <a href="${internship.link}" class="btn btn-small">Apply Now</a>
        </div>
    `;
}

function createTestimonialHTML(testimonial) {
    return `
        <div class="testimonial-card">
            <p>"${testimonial.text}"</p>
            <h4>- ${testimonial.name}</h4>
            <p>${testimonial.role}</p>
        </div>
    `;
}

// Code Editor Functionality
class CodeEditor {
    constructor(elementId) {
        this.editor = document.getElementById(elementId);
        this.setupEditor();
    }
    
    setupEditor() {
        if (this.editor) {
            this.editor.addEventListener('keydown', this.handleTab.bind(this));
        }
    }
    
    handleTab(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = this.editor.selectionStart;
            const end = this.editor.selectionEnd;
            
            this.editor.value = this.editor.value.substring(0, start) + '    ' + this.editor.value.substring(end);
            this.editor.selectionStart = this.editor.selectionEnd = start + 4;
        }
    }
    
    getCode() {
        return this.editor ? this.editor.value : '';
    }
    
    setCode(code) {
        if (this.editor) {
            this.editor.value = code;
        }
    }
}

// Initialize code editor if exists
const codeEditor = new CodeEditor('codeEditor');

// Export functions for use in other files
window.csHub = {
    showNotification,
    toggleDarkMode,
    filterItems,
    codeEditor
};
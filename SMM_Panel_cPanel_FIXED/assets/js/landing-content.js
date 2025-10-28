/**
 * Landing Page Content Loader
 * This script loads dynamic content from the admin panel
 */

class LandingContentLoader {
    constructor() {
        this.content = null;
        this.init();
    }
    
    async init() {
        try {
            await this.loadContent();
            this.renderContent();
        } catch (error) {
            console.error('Failed to load landing content:', error);
            this.renderDefaultContent();
        }
    }
    
    async loadContent() {
        try {
            const response = await fetch('api/landing.php?action=get_content');
            const data = await response.json();
            
            if (data.success && data.data) {
                this.content = data.data;
            } else {
                throw new Error('No content data');
            }
        } catch (error) {
            console.warn('Using default content');
            this.content = this.getDefaultContent();
        }
    }
    
    renderContent() {
        if (!this.content) return;
        
        // Render Hero Section
        this.renderHero();
        
        // Render Features
        this.renderFeatures();
        
        // Render Services
        this.renderServices();
        
        // Render Stats
        this.renderStats();
        
        // Render Testimonials
        this.renderTestimonials();
        
        // Render FAQ
        this.renderFAQ();
        
        // Render Footer
        this.renderFooter();
        
        // Update SEO
        this.updateSEO();
    }
    
    renderHero() {
        if (!this.content.hero) return;
        
        const heroTitle = document.querySelector('.hero h1');
        const heroSubtitle = document.querySelector('.hero .hero-subtitle');
        const heroCta = document.querySelector('.hero .hero-cta');
        
        if (heroTitle && this.content.hero.title) {
            heroTitle.textContent = this.content.hero.title;
        }
        
        if (heroSubtitle && this.content.hero.subtitle) {
            heroSubtitle.innerHTML = this.content.hero.subtitle;
        }
        
        if (heroCta && this.content.hero.cta) {
            heroCta.textContent = this.content.hero.cta;
        }
    }
    
    renderFeatures() {
        if (!this.content.features || !Array.isArray(this.content.features)) return;
        
        const featuresContainer = document.querySelector('.features-grid, #features-section');
        if (!featuresContainer) return;
        
        // Only render if container is empty or has placeholder
        if (featuresContainer.children.length === 0 || featuresContainer.querySelector('.placeholder')) {
            featuresContainer.innerHTML = this.content.features.map(feature => `
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="${feature.icon}"></i>
                    </div>
                    <h3>${feature.title}</h3>
                    <p>${feature.description}</p>
                </div>
            `).join('');
        }
    }
    
    renderServices() {
        if (!this.content.services || !Array.isArray(this.content.services)) return;
        
        const servicesContainer = document.querySelector('.services-grid, #services-section');
        if (!servicesContainer) return;
        
        if (servicesContainer.children.length === 0 || servicesContainer.querySelector('.placeholder')) {
            servicesContainer.innerHTML = this.content.services.map(service => `
                <div class="service-card">
                    <div class="service-icon">
                        <i class="${service.icon}"></i>
                    </div>
                    <h3>${service.title}</h3>
                    <p>${service.description}</p>
                </div>
            `).join('');
        }
    }
    
    renderStats() {
        if (!this.content.stats || !Array.isArray(this.content.stats)) return;
        
        const statsContainer = document.querySelector('.stats-section');
        if (!statsContainer) return;
        
        if (statsContainer.children.length === 0 || statsContainer.querySelector('.placeholder')) {
            statsContainer.innerHTML = this.content.stats.map(stat => `
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="${stat.icon}"></i>
                    </div>
                    <div class="stat-value">${stat.value}</div>
                    <div class="stat-label">${stat.label}</div>
                </div>
            `).join('');
        }
    }
    
    renderTestimonials() {
        if (!this.content.testimonials || !Array.isArray(this.content.testimonials)) return;
        
        const testimonialsContainer = document.querySelector('.testimonials-section');
        if (!testimonialsContainer) return;
        
        if (testimonialsContainer.children.length === 0 || testimonialsContainer.querySelector('.placeholder')) {
            testimonialsContainer.innerHTML = this.content.testimonials.map(testimonial => `
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        ${'★'.repeat(testimonial.rating)}
                    </div>
                    <p class="testimonial-text">${testimonial.text}</p>
                    <div class="testimonial-author">
                        ${testimonial.avatar ? `<img src="${testimonial.avatar}" alt="${testimonial.name}">` : ''}
                        <div>
                            <h4>${testimonial.name}</h4>
                            <p>${testimonial.position}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    }
    
    renderFAQ() {
        if (!this.content.faq || !Array.isArray(this.content.faq)) return;
        
        const faqContainer = document.querySelector('.faq-list, #faq-section');
        if (!faqContainer) return;
        
        if (faqContainer.children.length === 0 || faqContainer.querySelector('.placeholder')) {
            faqContainer.innerHTML = this.content.faq.map(faq => `
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>${faq.question}</h3>
                    </div>
                    <div class="faq-answer">
                        <p>${faq.answer}</p>
                    </div>
                </div>
            `).join('');
        }
    }
    
    renderFooter() {
        if (!this.content.footer) return;
        
        const footerCopyright = document.querySelector('.footer-copyright');
        const footerDescription = document.querySelector('.footer-description');
        
        if (footerCopyright && this.content.footer.copyright) {
            footerCopyright.innerHTML = this.content.footer.copyright;
        }
        
        if (footerDescription && this.content.footer.description) {
            footerDescription.textContent = this.content.footer.description;
        }
    }
    
    updateSEO() {
        if (!this.content.seo) return;
        
        // Update title
        if (this.content.seo.title) {
            document.title = this.content.seo.title;
        }
        
        // Update meta description
        const metaDesc = document.querySelector('meta[name="description"]');
        if (metaDesc && this.content.seo.description) {
            metaDesc.setAttribute('content', this.content.seo.description);
        }
        
        // Update meta keywords
        const metaKeywords = document.querySelector('meta[name="keywords"]');
        if (metaKeywords && this.content.seo.keywords) {
            metaKeywords.setAttribute('content', this.content.seo.keywords);
        }
    }
    
    getDefaultContent() {
        return {
            hero: {
                title: 'Grow Your Social Media Smarter, Faster, Cheaper',
                subtitle: '🚀 World\'s #1 SMM Panel • Lightning Fast Delivery • Best Prices Guaranteed',
                cta: 'Start Free Trial'
            },
            features: [],
            services: [],
            stats: [],
            testimonials: [],
            faq: [],
            footer: {
                copyright: '&copy; Copyright 2025. All Rights Reserved by SMM Turk.',
                description: 'World\'s Best Cheap & Easy SMM Panel. Your partner in business expansion for the last 8 years.'
            },
            seo: {
                title: 'SMM Turk - Best SMM Panel',
                description: 'Best SMM panel for social media services with fast delivery and competitive prices.',
                keywords: 'smm panel, social media marketing, instagram followers'
            }
        };
    }
    
    renderDefaultContent() {
        this.content = this.getDefaultContent();
        this.renderContent();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    window.landingContentLoader = new LandingContentLoader();
});


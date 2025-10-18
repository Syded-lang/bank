{{-- Credit Card Showcase Section --}}
<section class="credit-card-section py-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-header text-center">
                    <h2 class="section-title">Premium Banking Cards</h2>
                    <p class="mt-2">Experience secure and convenient banking with our premium cards</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-5">
            <div class="col-xl-10">
                <div class="row gy-4">
                    {{-- Card 1: Platinum Card --}}
                    <div class="col-lg-6">
                        <div class="credit-card platinum-card wow fadeInLeft" data-wow-duration="0.8s">
                            <div class="card-background">
                                <div class="card-chip">
                                    <svg width="50" height="40" viewBox="0 0 50 40">
                                        <rect width="50" height="40" rx="5" fill="#FFD700"/>
                                        <rect x="5" y="8" width="15" height="12" rx="2" fill="#FFA500"/>
                                        <rect x="22" y="8" width="15" height="12" rx="2" fill="#FFA500"/>
                                        <line x1="5" y1="25" x2="45" y2="25" stroke="#FFA500" stroke-width="2"/>
                                        <line x1="5" y1="30" x2="45" y2="30" stroke="#FFA500" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="card-logo">
                                    <span class="logo-text">{{ __(gs('site_name')) }}</span>
                                </div>
                                <div class="card-number">
                                    <span class="number-group">5412</span>
                                    <span class="number-group">7512</span>
                                    <span class="number-group">3456</span>
                                    <span class="number-group">7890</span>
                                </div>
                                <div class="card-details">
                                    <div class="card-holder">
                                        <span class="label">CARD HOLDER</span>
                                        <span class="value">AHMED ALI</span>
                                    </div>
                                    <div class="card-expiry">
                                        <span class="label">EXPIRES</span>
                                        <span class="value">12/25</span>
                                    </div>
                                </div>
                                <div class="card-type">
                                    <span class="badge-platinum">PLATINUM</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Gold Card --}}
                    <div class="col-lg-6">
                        <div class="credit-card gold-card wow fadeInRight" data-wow-duration="0.8s">
                            <div class="card-background">
                                <div class="card-chip">
                                    <svg width="50" height="40" viewBox="0 0 50 40">
                                        <rect width="50" height="40" rx="5" fill="#FFD700"/>
                                        <rect x="5" y="8" width="15" height="12" rx="2" fill="#FFA500"/>
                                        <rect x="22" y="8" width="15" height="12" rx="2" fill="#FFA500"/>
                                        <line x1="5" y1="25" x2="45" y2="25" stroke="#FFA500" stroke-width="2"/>
                                        <line x1="5" y1="30" x2="45" y2="30" stroke="#FFA500" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="card-logo">
                                    <span class="logo-text">{{ __(gs('site_name')) }}</span>
                                </div>
                                <div class="card-number">
                                    <span class="number-group">4532</span>
                                    <span class="number-group">8912</span>
                                    <span class="number-group">6734</span>
                                    <span class="number-group">5678</span>
                                </div>
                                <div class="card-details">
                                    <div class="card-holder">
                                        <span class="label">CARD HOLDER</span>
                                        <span class="value">FATIMA HASSAN</span>
                                    </div>
                                    <div class="card-expiry">
                                        <span class="label">EXPIRES</span>
                                        <span class="value">08/26</span>
                                    </div>
                                </div>
                                <div class="card-type">
                                    <span class="badge-gold">GOLD</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Features --}}
                <div class="row mt-5 gy-4">
                    <div class="col-md-3 col-sm-6">
                        <div class="card-feature text-center">
                            <div class="icon-box mb-3">
                                <i class="las la-shield-alt"></i>
                            </div>
                            <h5>Secure</h5>
                            <p class="small">EMV chip technology</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card-feature text-center">
                            <div class="icon-box mb-3">
                                <i class="las la-globe"></i>
                            </div>
                            <h5>Global</h5>
                            <p class="small">Accepted worldwide</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card-feature text-center">
                            <div class="icon-box mb-3">
                                <i class="las la-wifi"></i>
                            </div>
                            <h5>Contactless</h5>
                            <p class="small">Tap and pay enabled</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card-feature text-center">
                            <div class="icon-box mb-3">
                                <i class="las la-gift"></i>
                            </div>
                            <h5>Rewards</h5>
                            <p class="small">Cashback & points</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.credit-card-section {
    background: linear-gradient(135deg, hsl(var(--base)) 0%, hsl(var(--base-two)) 100%);
    position: relative;
    overflow: hidden;
}

.credit-card-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,96C1248,75,1344,53,1392,42.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat;
}

.credit-card-section .section-title,
.credit-card-section p {
    color: white;
}

.credit-card {
    perspective: 1000px;
    width: 100%;
    max-width: 450px;
    margin: 0 auto;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.credit-card:hover {
    transform: translateY(-10px);
}

.card-background {
    position: relative;
    padding: 30px;
    border-radius: 20px;
    height: 280px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.platinum-card .card-background {
    background: linear-gradient(135deg, hsl(var(--dark)) 0%, #1a202c 100%);
    color: white;
}

.gold-card .card-background {
    background: linear-gradient(135deg, hsl(var(--base)) 0%, hsl(var(--base-two)) 100%);
    color: white;
}

.card-background::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(-10%, -10%); }
}

.card-chip {
    width: 50px;
    height: 40px;
}

.card-logo {
    position: absolute;
    top: 30px;
    right: 30px;
}

.logo-text {
    font-size: 18px;
    font-weight: bold;
    letter-spacing: 2px;
    opacity: 0.9;
}

.card-number {
    display: flex;
    gap: 15px;
    font-size: 22px;
    font-weight: 500;
    letter-spacing: 2px;
    margin-top: 20px;
    font-family: 'Courier New', monospace;
}

.card-details {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 20px;
    gap: 15px;
}

.card-holder,
.card-expiry {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.card-holder {
    flex: 1;
}

.card-expiry {
    text-align: right;
    min-width: 80px;
}

.label {
    font-size: 9px;
    opacity: 0.8;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    font-weight: 500;
    white-space: nowrap;
}

.value {
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 1px;
    white-space: nowrap;
}

.card-type {
    position: absolute;
    bottom: 30px;
    right: 30px;
}

.badge-platinum,
.badge-gold {
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    letter-spacing: 1px;
}

.badge-platinum {
    background: rgba(255,255,255,0.2);
    color: white;
    backdrop-filter: blur(10px);
}

.badge-gold {
    background: rgba(255,255,255,0.3);
    color: white;
    backdrop-filter: blur(10px);
}

.card-feature {
    padding: 20px;
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.card-feature:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-5px);
}

.card-feature .icon-box {
    font-size: 48px;
    color: white;
}

.card-feature h5 {
    color: white;
    font-weight: bold;
    margin-bottom: 5px;
}

.card-feature p {
    color: rgba(255,255,255,0.8);
    margin: 0;
}

@media (max-width: 768px) {
    .card-number {
        font-size: 18px;
        gap: 10px;
    }

    .card-background {
        padding: 20px;
        height: 240px;
    }
}
</style>

<script>
// Add 3D tilt effect to cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.credit-card');

    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
        });
    });
});
</script>

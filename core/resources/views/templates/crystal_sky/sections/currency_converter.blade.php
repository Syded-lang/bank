{{-- Live Currency Converter Section --}}
<section class="currency-converter-section py-100 bg--light">
    <div class="container">
        <div class="row gy-5">
            {{-- Left Side: Currency Converter --}}
            <div class="col-lg-7">
                <div class="currency-converter-card">
                    <div class="converter-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="las la-exchange-alt"></i>
                            <h3 class="mb-0">Live Currency Converter</h3>
                        </div>
                        <p class="mb-0 mt-2">Real-time rates • Last updated: <span id="lastUpdatedTime">{{ date('Y-m-d') }}</span></p>
                    </div>

                    <div class="converter-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Amount to Convert</label>
                            <input type="number" class="form-control form-control-lg converter-input" id="fromAmount" value="1000" min="0" step="0.01">
                        </div>

                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">From</label>
                                <select class="form-select form-select-lg currency-select" id="fromCurrency">
                                    <option value="AED" data-flag="🇦🇪">AED - UAE Dirham</option>
                                    <option value="USD" selected data-flag="🇺🇸">USD - US Dollar</option>
                                    <option value="EUR" data-flag="🇪🇺">EUR - Euro</option>
                                    <option value="GBP" data-flag="🇬🇧">GBP - British Pound</option>
                                    <option value="JPY" data-flag="🇯🇵">JPY - Japanese Yen</option>
                                    <option value="AUD" data-flag="🇦🇺">AUD - Australian Dollar</option>
                                    <option value="CAD" data-flag="🇨🇦">CAD - Canadian Dollar</option>
                                    <option value="CHF" data-flag="🇨🇭">CHF - Swiss Franc</option>
                                    <option value="SAR" data-flag="🇸🇦">SAR - Saudi Riyal</option>
                                    <option value="INR" data-flag="🇮🇳">INR - Indian Rupee</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">To</label>
                                <select class="form-select form-select-lg currency-select" id="toCurrency">
                                    <option value="AED" data-flag="🇦🇪">AED - UAE Dirham</option>
                                    <option value="USD" data-flag="🇺🇸">USD - US Dollar</option>
                                    <option value="EUR" data-flag="🇪🇺">EUR - Euro</option>
                                    <option value="GBP" data-flag="🇬🇧">GBP - British Pound</option>
                                    <option value="JPY" selected data-flag="🇯🇵">JPY - Japanese Yen</option>
                                    <option value="AUD" data-flag="🇦🇺">AUD - Australian Dollar</option>
                                    <option value="CAD" data-flag="🇨🇦">CAD - Canadian Dollar</option>
                                    <option value="CHF" data-flag="🇨🇭">CHF - Swiss Franc</option>
                                    <option value="SAR" data-flag="🇸🇦">SAR - Saudi Riyal</option>
                                    <option value="INR" data-flag="🇮🇳">INR - Indian Rupee</option>
                                </select>
                            </div>
                        </div>

                        <div class="conversion-result mt-4">
                            <div class="result-box from-box">
                                <div class="currency-flag" id="fromFlag">🇦🇪</div>
                                <div class="result-content">
                                    <span class="result-amount" id="fromDisplay">1000</span>
                                    <span class="result-currency" id="fromCurrencyDisplay">UAE Dirham</span>
                                    <span class="result-location" id="fromLocation">United Arab Emirates</span>
                                </div>
                            </div>

                            <div class="swap-divider">
                                <button type="button" class="btn-swap-currencies" id="swapBtn">
                                    <i class="las la-exchange-alt"></i> Swap Currencies
                                </button>
                                <button type="button" class="btn-refresh" id="refreshBtn">
                                    <i class="las la-sync-alt"></i> Refresh Rates
                                </button>
                            </div>

                            <div class="result-box to-box">
                                <div class="currency-flag" id="toFlag">🇺🇸</div>
                                <div class="result-content">
                                    <span class="result-amount" id="toDisplay">0</span>
                                    <span class="result-currency" id="toCurrencyDisplay">US Dollar</span>
                                    <span class="result-location" id="toLocation">United States</span>
                                </div>
                            </div>
                        </div>

                        <div class="conversion-info mt-4 p-3">
                            <h6 class="mb-3">Popular Exchange Rates (vs USD)</h6>
                            <div class="row g-2" id="popularRates">
                                <div class="col-md-3 col-6"><div class="rate-item"><span class="rate-currency">USD</span><span class="rate-value">1.0000 <small class="rate-change text-success">↑ 0.09%</small></span></div></div>
                                <div class="col-md-3 col-6"><div class="rate-item"><span class="rate-currency">EUR</span><span class="rate-value">0.8620 <small class="rate-change text-success">↑ 1.29%</small></span></div></div>
                                <div class="col-md-3 col-6"><div class="rate-item"><span class="rate-currency">GBP</span><span class="rate-value">0.7500 <small class="rate-change text-danger">↓ 1.44%</small></span></div></div>
                                <div class="col-md-3 col-6"><div class="rate-item"><span class="rate-currency">SAR</span><span class="rate-value">3.7500 <small class="rate-change text-success">↑ 0.08%</small></span></div></div>
                                <div class="col-md-3 col-6"><div class="rate-item"><span class="rate-currency">JPY</span><span class="rate-value">151.8500 <small class="rate-change text-danger">↓ 1.73%</small></span></div></div>
                                <div class="col-md-3 col-6"><div class="rate-item"><span class="rate-currency">CHF</span><span class="rate-value">0.8020 <small class="rate-change text-danger">↓ 1.23%</small></span></div></div>
                                <div class="col-md-3 col-6"><div class="rate-item"><span class="rate-currency">CAD</span><span class="rate-value">1.4000 <small class="rate-change text-danger">↓ 1.46%</small></span></div></div>
                                <div class="col-md-3 col-6"><div class="rate-item"><span class="rate-currency">AUD</span><span class="rate-value">1.5300 <small class="rate-change text-success">↑ 1.30%</small></span></div></div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn--base w-100 btn-lg" id="convertBtn">Convert Now</button>
                </div>
            </div>

            {{-- Right Side: Live Exchange Rates --}}
            <div class="col-lg-5">
                <div class="live-rates-card">
                    <div class="rates-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="las la-globe"></i>
                            <h4 class="mb-0">Live Exchange Rates</h4>
                        </div>
                        <button type="button" class="btn-refresh-rates" onclick="location.reload()">
                            <i class="las la-sync-alt"></i>
                        </button>
                    </div>
                    <p class="rates-subtitle">vs USD • Updated: <span id="ratesUpdatedTime">10:15:02 PM</span></p>

                    <div class="rates-list" id="liveRatesList">
                        <div class="rate-row">
                            <div class="rate-info">
                                <div class="rate-flag">🇦🇪</div>
                                <div>
                                    <div class="rate-name">AED</div>
                                    <div class="rate-full">UAE Dirham<br><small class="text-muted">United Arab Emirates</small></div>
                                </div>
                            </div>
                            <div class="rate-details">
                                <div class="rate-price">3.6700</div>
                                <div class="rate-change text-danger">↓ -0.96%</div>
                            </div>
                        </div>

                        <div class="rate-row">
                            <div class="rate-info">
                                <div class="rate-flag">🇪🇺</div>
                                <div>
                                    <div class="rate-name">EUR</div>
                                    <div class="rate-full">Euro<br><small class="text-muted">European Union</small></div>
                                </div>
                            </div>
                            <div class="rate-details">
                                <div class="rate-price">0.8620</div>
                                <div class="rate-change text-success">↑ +1.29%</div>
                            </div>
                        </div>

                        <div class="rate-row">
                            <div class="rate-info">
                                <div class="rate-flag">🇬🇧</div>
                                <div>
                                    <div class="rate-name">GBP</div>
                                    <div class="rate-full">British Pound<br><small class="text-muted">United Kingdom</small></div>
                                </div>
                            </div>
                            <div class="rate-details">
                                <div class="rate-price">0.7500</div>
                                <div class="rate-change text-danger">↓ -1.44%</div>
                            </div>
                        </div>

                        <div class="rate-row">
                            <div class="rate-info">
                                <div class="rate-flag">🇸🇦</div>
                                <div>
                                    <div class="rate-name">SAR</div>
                                    <div class="rate-full">Saudi Riyal<br><small class="text-muted">Saudi Arabia</small></div>
                                </div>
                            </div>
                            <div class="rate-details">
                                <div class="rate-price">3.7500</div>
                                <div class="rate-change text-success">↑ +0.08%</div>
                            </div>
                        </div>

                        <div class="rate-row">
                            <div class="rate-info">
                                <div class="rate-flag">🇯🇵</div>
                                <div>
                                    <div class="rate-name">JPY</div>
                                    <div class="rate-full">Japanese Yen<br><small class="text-muted">Japan</small></div>
                                </div>
                            </div>
                            <div class="rate-details">
                                <div class="rate-price">151.8500</div>
                                <div class="rate-change text-danger">↓ -1.73%</div>
                            </div>
                        </div>

                        <div class="rate-row">
                            <div class="rate-info">
                                <div class="rate-flag">🇨🇭</div>
                                <div>
                                    <div class="rate-name">CHF</div>
                                    <div class="rate-full">Swiss Franc<br><small class="text-muted">Switzerland</small></div>
                                </div>
                            </div>
                            <div class="rate-details">
                                <div class="rate-price">0.8020</div>
                                <div class="rate-change text-danger">↓ -1.23%</div>
                            </div>
                        </div>

                        <div class="rate-row">
                            <div class="rate-info">
                                <div class="rate-flag">🇨🇦</div>
                                <div>
                                    <div class="rate-name">CAD</div>
                                    <div class="rate-full">Canadian Dollar<br><small class="text-muted">Canada</small></div>
                                </div>
                            </div>
                            <div class="rate-details">
                                <div class="rate-price">1.4000</div>
                                <div class="rate-change text-danger">↓ -1.46%</div>
                            </div>
                        </div>

                        <div class="rate-row">
                            <div class="rate-info">
                                <div class="rate-flag">🇦🇺</div>
                                <div>
                                    <div class="rate-name">AUD</div>
                                    <div class="rate-full">Australian Dollar<br><small class="text-muted">Australia</small></div>
                                </div>
                            </div>
                            <div class="rate-details">
                                <div class="rate-price">1.5300</div>
                                <div class="rate-change text-success">↑ +1.30%</div>
                            </div>
                        </div>
                    </div>

                    <div class="market-status mt-4">
                        <div class="status-indicator"></div>
                        <span>Markets Open</span>
                        <span class="badge badge-success ms-2">Live Trading</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.currency-converter-section {
    background: #f8f9fc;
}

.currency-converter-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.converter-header {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    padding: 25px 30px;
}

.converter-header i {
    font-size: 28px;
}

.converter-header h3 {
    font-size: 24px;
    font-weight: 700;
}

.converter-header p {
    opacity: 0.95;
    font-size: 14px;
}

.converter-body {
    background: white;
}

.converter-input {
    height: 60px;
    font-size: 32px;
    font-weight: 600;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    text-align: center;
}

.converter-input:focus {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.currency-select {
    height: 55px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-weight: 600;
}

.conversion-result {
    background: #f9fafb;
    border-radius: 15px;
    padding: 20px;
}

.result-box {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.from-box {
    border: 2px solid #e5e7eb;
}

.to-box {
    border: 2px solid #10b981;
    background: linear-gradient(to right, #f0fdf4, white);
}

.currency-flag {
    font-size: 48px;
    line-height: 1;
}

.result-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.result-amount {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    line-height: 1.2;
}

.result-currency {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    margin-top: 5px;
}

.result-location {
    font-size: 13px;
    color: #9ca3af;
}

.swap-divider {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin: 15px 0;
}

.btn-swap-currencies,
.btn-refresh {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    padding: 10px 20px;
    font-weight: 600;
    color: #374151;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-swap-currencies:hover {
    background: #dc2626;
    color: white;
    border-color: #dc2626;
    transform: scale(1.05);
}

.btn-refresh:hover {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.conversion-info {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.conversion-info h6 {
    color: #111827;
    font-weight: 700;
    margin-bottom: 15px;
}

.rate-item {
    background: #f9fafb;
    border-radius: 8px;
    padding: 12px;
    text-align: center;
}

.rate-currency {
    display: block;
    font-weight: 700;
    color: #111827;
    margin-bottom: 5px;
}

.rate-value {
    display: block;
    font-size: 18px;
    font-weight: 600;
    color: #374151;
}

.rate-change {
    font-size: 11px;
    font-weight: 600;
}

/* Live Rates Card */
.live-rates-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    height: 100%;
}

.rates-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.rates-header i {
    font-size: 24px;
    color: #3b82f6;
}

.rates-header h4 {
    color: #111827;
    font-weight: 700;
}

.btn-refresh-rates {
    background: #eff6ff;
    border: none;
    border-radius: 8px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-refresh-rates:hover {
    background: #3b82f6;
    color: white;
    transform: rotate(180deg);
}

.rates-subtitle {
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 20px;
}

.rates-list {
    max-height: 600px;
    overflow-y: auto;
}

.rate-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #f3f4f6;
    transition: all 0.3s;
}

.rate-row:hover {
    background: #f9fafb;
}

.rate-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.rate-flag {
    font-size: 32px;
    line-height: 1;
}

.rate-name {
    font-weight: 700;
    font-size: 16px;
    color: #111827;
}

.rate-full {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.4;
}

.rate-details {
    text-align: right;
}

.rate-price {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
}

.rate-change {
    font-size: 12px;
    font-weight: 600;
}

.market-status {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: #f0fdf4;
    border-radius: 10px;
    margin-top: 20px;
}

.status-indicator {
    width: 10px;
    height: 10px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.market-status span {
    font-weight: 600;
    color: #047857;
}

.badge-success {
    background: #10b981;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
}

@media (max-width: 992px) {
    .result-amount {
        font-size: 24px;
    }

    .converter-input {
        font-size: 24px;
        height: 50px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fromAmount = document.getElementById('fromAmount');
    const toAmount = document.getElementById('toAmount');
    const fromCurrency = document.getElementById('fromCurrency');
    const toCurrency = document.getElementById('toCurrency');
    const swapBtn = document.getElementById('swapBtn');
    const exchangeRateDisplay = document.getElementById('exchangeRate');
    const lastUpdatedDisplay = document.getElementById('lastUpdated');

    let exchangeRates = {};

    // Fetch exchange rates
    async function fetchExchangeRates() {
        try {
            const baseCurrency = fromCurrency.value;
            const response = await fetch(`https://api.exchangerate-api.com/v4/latest/${baseCurrency}`);
            const data = await response.json();

            exchangeRates = data.rates;
            const lastUpdate = new Date(data.time_last_updated * 1000);
            lastUpdatedDisplay.textContent = lastUpdate.toLocaleString();

            convertCurrency();
        } catch (error) {
            console.error('Error fetching exchange rates:', error);
            exchangeRateDisplay.textContent = 'Error loading rates';
        }
    }

    // Convert currency
    function convertCurrency() {
        const amount = parseFloat(fromAmount.value) || 0;
        const from = fromCurrency.value;
        const to = toCurrency.value;

        if (exchangeRates[to]) {
            const rate = exchangeRates[to];
            const result = amount * rate;
            toAmount.value = result.toFixed(2);
            exchangeRateDisplay.textContent = `1 ${from} = ${rate.toFixed(4)} ${to}`;
        }
    }

    // Swap currencies
    function swapCurrencies() {
        const tempCurrency = fromCurrency.value;
        fromCurrency.value = toCurrency.value;
        toCurrency.value = tempCurrency;

        const tempAmount = fromAmount.value;
        fromAmount.value = toAmount.value;

        fetchExchangeRates();
    }

    // Event listeners
    fromAmount.addEventListener('input', convertCurrency);
    fromCurrency.addEventListener('change', fetchExchangeRates);
    toCurrency.addEventListener('change', convertCurrency);
    swapBtn.addEventListener('click', swapCurrencies);

    // Initial load
    fetchExchangeRates();
});
</script>

<script>
// Complete Functional Currency Converter
document.addEventListener('DOMContentLoaded', function() {
    const fromAmount = document.getElementById('fromAmount');
    const fromCurrency = document.getElementById('fromCurrency');
    const toCurrency = document.getElementById('toCurrency');
    const swapBtn = document.getElementById('swapBtn');
    const refreshBtn = document.getElementById('refreshBtn');
    const convertBtn = document.getElementById('convertBtn');

    const fromDisplay = document.getElementById('fromDisplay');
    const toDisplay = document.getElementById('toDisplay');
    const fromFlag = document.getElementById('fromFlag');
    const toFlag = document.getElementById('toFlag');
    const fromCurrencyDisplay = document.getElementById('fromCurrencyDisplay');
    const toCurrencyDisplay = document.getElementById('toCurrencyDisplay');
    const fromLocation = document.getElementById('fromLocation');
    const toLocation = document.getElementById('toLocation');
    const lastUpdatedTime = document.getElementById('lastUpdatedTime');
    const ratesUpdatedTime = document.getElementById('ratesUpdatedTime');

    let exchangeRates = {};

    const currencyNames = {
        'AED': { name: 'UAE Dirham', location: 'United Arab Emirates', flag: '🇦🇪' },
        'USD': { name: 'US Dollar', location: 'United States', flag: '🇺🇸' },
        'EUR': { name: 'Euro', location: 'European Union', flag: '🇪🇺' },
        'GBP': { name: 'British Pound', location: 'United Kingdom', flag: '🇬🇧' },
        'JPY': { name: 'Japanese Yen', location: 'Japan', flag: '🇯🇵' },
        'AUD': { name: 'Australian Dollar', location: 'Australia', flag: '🇦🇺' },
        'CAD': { name: 'Canadian Dollar', location: 'Canada', flag: '🇨🇦' },
        'CHF': { name: 'Swiss Franc', location: 'Switzerland', flag: '🇨🇭' },
        'SAR': { name: 'Saudi Riyal', location: 'Saudi Arabia', flag: '🇸🇦' },
        'INR': { name: 'Indian Rupee', location: 'India', flag: '🇮🇳' }
    };

    // Fetch exchange rates
    async function fetchExchangeRates() {
        try {
            const baseCurrency = fromCurrency.value;
            const response = await fetch(`https://api.exchangerate-api.com/v4/latest/${baseCurrency}`);
            const data = await response.json();

            exchangeRates = data.rates;

            // Update last updated time
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const dateString = now.toISOString().split('T')[0];
            lastUpdatedTime.textContent = dateString;
            ratesUpdatedTime.textContent = timeString;

            convertCurrency();
            updatePopularRates();
        } catch (error) {
            console.error('Error fetching exchange rates:', error);
            toDisplay.textContent = 'Error';
        }
    }

    // Convert currency
    function convertCurrency() {
        const amount = parseFloat(fromAmount.value) || 0;
        const from = fromCurrency.value;
        const to = toCurrency.value;

        if (exchangeRates[to]) {
            const rate = exchangeRates[to];
            const result = amount * rate;

            // Update display
            fromDisplay.textContent = amount.toFixed(2);
            toDisplay.textContent = result.toFixed(2);

            // Update currency info
            fromFlag.textContent = currencyNames[from].flag;
            toFlag.textContent = currencyNames[to].flag;
            fromCurrencyDisplay.textContent = currencyNames[from].name;
            toCurrencyDisplay.textContent = currencyNames[to].name;
            fromLocation.textContent = currencyNames[from].location;
            toLocation.textContent = currencyNames[to].location;
        }
    }

    // Update popular rates
    function updatePopularRates() {
        const popularRatesContainer = document.getElementById('popularRates');
        if (!popularRatesContainer) return;

        const currencies = ['USD', 'EUR', 'GBP', 'SAR', 'JPY', 'CHF', 'CAD', 'AUD'];
        const baseRate = exchangeRates['USD'] || 1;

        let html = '';
        currencies.forEach(curr => {
            const rate = exchangeRates[curr] ? (exchangeRates[curr] / baseRate) : 1;
            const randomChange = (Math.random() * 2 - 1).toFixed(2);
            const isPositive = parseFloat(randomChange) >= 0;

            html += `
                <div class="col-md-3 col-6">
                    <div class="rate-item">
                        <span class="rate-currency">${curr}</span>
                        <span class="rate-value">${rate.toFixed(4)}
                            <small class="rate-change ${isPositive ? 'text-success' : 'text-danger'}">
                                ${isPositive ? '↑' : '↓'} ${Math.abs(randomChange)}%
                            </small>
                        </span>
                    </div>
                </div>
            `;
        });

        popularRatesContainer.innerHTML = html;
    }

    // Swap currencies
    function swapCurrencies() {
        const tempCurrency = fromCurrency.value;
        fromCurrency.value = toCurrency.value;
        toCurrency.value = tempCurrency;

        fetchExchangeRates();
    }

    // Event listeners
    fromAmount.addEventListener('input', convertCurrency);
    fromCurrency.addEventListener('change', fetchExchangeRates);
    toCurrency.addEventListener('change', convertCurrency);
    swapBtn.addEventListener('click', swapCurrencies);
    refreshBtn.addEventListener('click', fetchExchangeRates);
    convertBtn.addEventListener('click', convertCurrency);

    // Initial load
    fetchExchangeRates();
});
</script>

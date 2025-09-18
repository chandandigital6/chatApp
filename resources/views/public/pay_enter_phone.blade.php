@extends('component.main')
@section('content')
    <!-- Hero / Breadcrumb -->
    <div class="container-fluid custom-color my-lg-5 py-md-4 py-sm-3 py-2">
        <div class="container text-center py-5">
            <h1 class="display-2 text-white mb-2 animated slideInDown">Secure Payment</h1>
            <nav aria-label="breadcrumb" class="animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Payment</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Page -->
    <div class="container my-5">
        <div class="mx-auto" style="max-width: 960px;">
            <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-100">

                <!-- Top strip -->
                <div class="px-4 px-md-5 py-3 border-bottom bg-light">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;background:#e6f4ff;">
                            <svg width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#0ea5e9"
                                    d="M17 8h-1V6a4 4 0 1 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2m-6 0V6a2 2 0 1 1 4 0v2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="fw-bold mb-0">Complete Your Payment</div>
                            <small class="text-muted">Step 1: Verify phone → Step 2: Pay securely</small>
                        </div>
                    </div>
                </div>

                <div class="row g-0">
                    <!-- LEFT: Summary -->
                    @php
                        // MRP (GST inclusive)
                        $mrp = $order->amount ?? ($order->package->sale_price ?? ($order->package->price ?? 0));

                        // Discount math
                        $discVal = 0.0;
                        if (($order->discount_type ?? 'none') === 'flat') {
                            $discVal = min((float) $order->discount_value, (float) $mrp);
                        } elseif (($order->discount_type ?? 'none') === 'percent') {
                            $discVal = min(((float) $mrp * (float) $order->discount_value) / 100, (float) $mrp);
                        }

                        $final = max($mrp - $discVal, 0);

                        // Back-calc GST just for display from MRP (inclusive)
                        $gstRate = 18;
                        $baseWithoutGST = $mrp > 0 ? round($mrp / (1 + $gstRate / 100), 2) : 0;
                        $gstAmt = max(round($mrp - $baseWithoutGST, 2), 0);

                        $discLabel = match ($order->discount_type) {
                            'flat' => 'Flat',
                            'percent' => $order->discount_value + 0 . '%',
                            default => '—',
                        };
                    @endphp

                    <div class="col-lg-5 border-end">
                        <div class="p-4 p-md-5">
                            <div class="mb-3 text-uppercase small fw-semibold text-secondary">Order Summary</div>

                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-3 border p-2"
                                    style="width:64px;height:64px;display:flex;align-items:center;justify-content:center;">
                                    <svg width="28" height="28" viewBox="0 0 24 24">
                                        <path fill="#334155" d="M21 8l-9-5l-9 5l9 5l9-5m-9 7l-9-5v6l9 5l9-5v-6l-9 5Z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="h5 mb-1">{{ $order->package->name ?? 'Package' }}</div>
                                    @if ($order->expires_at)
                                        <div class="small text-muted">Link expires:
                                            {{ $order->expires_at->format('d M Y, h:i A') }}</div>
                                    @endif
                                    @if (!empty($order->business_name))
                                        <div class="small mt-1"><span class="text-muted">For:</span>
                                            {{ $order->business_name }}</div>
                                    @endif
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="table-responsive">
                                <table class="table align-middle mb-2">
                                    <tbody class="small">
                                        <tr>
                                            <td class="text-muted">MRP (Incl. GST)</td>
                                            <td class="text-end fw-semibold">₹{{ number_format($mrp, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Base Price (Excl. GST)</td>
                                            <td class="text-end">₹{{ number_format($baseWithoutGST, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">GST @ 18% (already included)</td>
                                            <td class="text-end">₹{{ number_format($gstAmt, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                Discount
                                                <span
                                                    class="badge rounded-pill bg-success-subtle border border-success-subtle text-success">{{ $discLabel }}</span>
                                            </td>
                                            <td class="text-end text-success">− ₹{{ number_format($discVal, 2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-muted">Final Payable</th>
                                            <th class="text-end h5 mb-0 text-success">₹{{ number_format($final, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="small text-muted">
                                GST @ 18% is already included in the MRP. Discount is applied on the GST-inclusive price.
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: Verify & Pay -->
                    <div class="col-lg-7">
                        <div class="p-4 p-md-5">
                            <div class="mb-3 text-uppercase small fw-semibold text-secondary">Verify & Pay</div>

                            @if ($errors->any())
                                <div class="alert alert-danger py-2 px-3 mb-3">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <div id="msg" class="alert alert-danger py-2 px-3 mb-3 d-none" role="alert"
                                aria-live="polite"></div>

                            <div class="mb-2 fw-medium">Enter your phone (91XXXXXXXXXX)</div>
                            <div class="d-flex gap-2">
                                <input id="phone" value="{{ old('phone', $order->phone) }}" minlength="12"
                                    maxlength="12" pattern="^91[0-9]{10}$" class="form-control form-control-lg"
                                    placeholder="91XXXXXXXXXX" autocomplete="tel" inputmode="numeric">
                                <button id="checkBtn" class="btn btn-dark btn-lg px-4" type="button">
                                    <span class="check-text">Check</span>
                                    <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                                </button>
                            </div>
                            <div class="form-text">Must start with <b>91</b> and have 10 digits after that.</div>

                            <div class="d-flex align-items-center gap-2 mt-3 small">
                                <span id="icon" class="d-none" role="img" aria-label="Verified">✅</span>
                                <span id="statusTxt" class="text-muted">Please verify your number to continue.</span>
                            </div>

                            <form id="payForm" class="mt-4">
                                @csrf
                                <input type="hidden" name="phone" id="phoneHidden">
                                <button id="payBtn" type="button" disabled
                                    class="btn btn-success btn-lg w-100 disabled">
                                    Pay Now
                                </button>
                                <div class="text-center text-muted small mt-2">You’ll be redirected to secure Razorpay
                                    checkout.</div>
                            </form>

                            <hr class="my-4">

                            <div class="d-flex align-items-center justify-content-center gap-4 flex-wrap opacity-75">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/4/49/Visa_2021.svg" height="22"
                                    alt="Visa">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png"
                                    height="22" alt="Mastercard">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/4/4a/RuPay.svg" height="22"
                                    alt="RuPay">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/UPI-Logo-vector.svg"
                                    height="22" alt="UPI">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Razorpay_logo.svg"
                                    height="22" alt="Razorpay">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .disabled {
            pointer-events: none;
            opacity: .65;
            cursor: not-allowed;
        }
    </style>

    <!-- Razorpay Checkout -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        // ====== ELEMENTS ======
        const phone = document.getElementById('phone');
        const checkBtn = document.getElementById('checkBtn');
        const payBtn = document.getElementById('payBtn');
        const phoneHid = document.getElementById('phoneHidden');
        const icon = document.getElementById('icon');
        const statusTxt = document.getElementById('statusTxt');
        const msg = document.getElementById('msg');
        const spinner = checkBtn.querySelector('.spinner-border');
        const checkTxt = checkBtn.querySelector('.check-text');

        // ====== ROUTES & CSRF ======
        const ROUTE_CHECK = "{{ route('paylink.check', $order->link_token) }}";
        const ROUTE_INITIATE = "{{ route('paylink.initiate', $order->link_token) }}";
        const ROUTE_VERIFY = "{{ route('paylink.verify') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";

        // ====== HELPERS ======
        function showError(text) {
            msg.classList.remove('d-none');
            msg.classList.add('alert-danger');
            msg.classList.remove('alert-success');
            msg.textContent = text || 'Something went wrong.';
        }

        function showInfo(text) {
            msg.classList.remove('d-none');
            msg.classList.remove('alert-danger');
            msg.classList.add('alert-success');
            msg.textContent = text || 'Done.';
        }

        function resetState() {
            icon.classList.add('d-none');
            statusTxt.textContent = 'Please verify your number to continue.';
            payBtn.setAttribute('disabled', 'disabled');
            payBtn.classList.add('disabled');
            msg.classList.add('d-none');
        }

        function loadingCheck(b) {
            if (b) {
                spinner.classList.remove('d-none');
                checkTxt.classList.add('d-none');
                checkBtn.setAttribute('disabled', 'disabled');
            } else {
                spinner.classList.add('d-none');
                checkTxt.classList.remove('d-none');
                checkBtn.removeAttribute('disabled');
            }
        }

        function togglePayLoading(b) {
            if (b) {
                payBtn.innerHTML = 'Processing...';
                payBtn.setAttribute('disabled', 'disabled');
                payBtn.classList.add('disabled');
            } else {
                payBtn.innerHTML = 'Pay Now';
                // enable/disable managed elsewhere
            }
        }

        // Default to 91 on load
        document.addEventListener('DOMContentLoaded', () => {
            if (!phone.value) phone.value = '91';
        });

        // Sanitize input: only digits, max 12, must start with 91
        phone.addEventListener('input', () => {
            phone.value = phone.value.replace(/\D/g, '').slice(0, 12);
            if (!phone.value.startsWith('91')) phone.value = '91';
            resetState();
        });

        // ====== PHONE CHECK ======
        checkBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            resetState();
            loadingCheck(true);
            const val = (phone.value || '').trim();

            try {
                const res = await fetch(ROUTE_CHECK, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        phone: val
                    })
                });
                const data = await res.json();

                if (res.ok && data.ok) {
                    icon.classList.remove('d-none');
                    statusTxt.textContent = 'Number verified.';
                    payBtn.removeAttribute('disabled');
                    payBtn.classList.remove('disabled');
                    phoneHid.value = val;
                } else {
                    showError((data && data.msg) ? data.msg : 'Number not match');
                }
            } catch (e) {
                showError('Something went wrong. Try again.');
            } finally {
                loadingCheck(false);
            }
        });

        // ====== INITIATE + RZP OPEN + VERIFY ======
        payBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            if (payBtn.hasAttribute('disabled')) return;

            const phoneVal = (document.getElementById('phoneHidden').value || document.getElementById('phone')
                .value || '').trim();

            try {
                togglePayLoading(true);
                msg.classList.add('d-none');

                // 1) Create Razorpay order via AJAX
                const res = await fetch(ROUTE_INITIATE, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        phone: phoneVal
                    })
                });

                const data = await res.json();

                if (!res.ok || !data.ok) {
                    showError((data && data.msg) ? data.msg : 'Unable to initiate payment.');
                    togglePayLoading(false);
                    return;
                }

                if ((data.amount_paise | 0) <= 0) {
                    showError('Amount must be greater than ₹0.');
                    togglePayLoading(false);
                    return;
                }
                if (!data.key) {
                    showError('Configuration error: Razorpay key missing.');
                    togglePayLoading(false);
                    return;
                }

                // 2) Open Razorpay
                const rzp = new Razorpay({
                    key: data.key,
                    order_id: data.order_id,
                    amount: data.amount_paise,
                    currency: "INR",
                    name: {{ Js::from(config('app.name')) }},
                    description: {{ Js::from(($order->package->name ?? 'Package') . ' — Link Payment') }},
                    prefill: {
                        name: data.customer_name || "",
                        contact: data.customer_phone || ""
                    },
                    notes: {
                        payment_order_id: "{{ $order->id }}",
                        link_token: data.token,
                        phone: data.customer_phone || ""
                    },

                    // 3) Success -> AJAX verify and redirect to Thank-You
                    handler: function(resp) {
                        const phoneVal = (document.getElementById('phoneHidden').value || document
                            .getElementById('phone').value || '').trim();

                        fetch("{{ route('paylink.verify') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json",
                                    "X-Requested-With": "XMLHttpRequest"
                                },
                                body: JSON.stringify({
                                    razorpay_payment_id: resp.razorpay_payment_id,
                                    razorpay_order_id: resp.razorpay_order_id,
                                    razorpay_signature: resp.razorpay_signature,
                                    phone: phoneVal // ✅ send phone with verify
                                })
                            })
                            .then(r => r.json())
                            .then(payload => {
                                if (payload.ok && payload.redirect) {
                                    window.location.href = payload.redirect;
                                } else {
                                    msg.classList.remove('d-none');
                                    msg.textContent = (payload && payload.message) ? payload
                                        .message : 'Verification failed.';
                                    togglePayLoading(false);
                                }
                            })
                            .catch(() => {
                                msg.classList.remove('d-none');
                                msg.textContent = 'Network error while verifying.';
                                togglePayLoading(false);
                            });
                    },
                    modal: {
                        ondismiss: function() {
                            showError('Payment window closed. You can try again.');
                            togglePayLoading(false);
                        }
                    },
                    theme: {
                        color: "#0ea5e9"
                    }
                });

                rzp.on('payment.failed', function() {
                    showError('Payment failed. Please try again.');
                    togglePayLoading(false);
                });

                rzp.open();

            } catch (err) {
                console.error(err);
                showError('Something went wrong. Please try again.');
                togglePayLoading(false);
            }
        });
    </script>
@endsection

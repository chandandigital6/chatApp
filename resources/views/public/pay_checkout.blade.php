@extends('component.main')
@section('content')

<style>
  /* page-scope tweaks */
  .ck-card{ border:1px solid #eef0f4; box-shadow:0 8px 24px rgba(16,24,40,.06); border-radius:18px; }
  .ck-icon-20{width:20px;height:20px}
  .ck-icon-24{width:24px;height:24px}
  .ck-icon-28{width:28px;height:28px}
  .ck-soft{ background: linear-gradient(90deg,#f8fbff,#f6f9ff); }
</style>

{{-- HERO / BREADCRUMB --}}
<section class="py-5" style="background:#e10613;">  {{-- your brand red --}}
  <div class="container text-center text-white">
    <h1 class="fw-bolder display-6 mb-1">Secure Payment</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a class="link-light text-decoration-none" href="/">Home</a></li>
        <li class="breadcrumb-item active text-white-50">Payment</li>
      </ol>
    </nav>
  </div>
</section>

{{-- CHECKOUT BODY --}}
<section class="py-5 bg-light">
  <div class="container">

    <div class="ck-card p-0 overflow-hidden">
      {{-- top strip --}}
      <div class="px-4 px-md-5 py-3 border-bottom bg-white">
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:42px;height:42px;background:#e8f4ff;">
            <svg class="ck-icon-24" viewBox="0 0 24 24" fill="#0d6efd"><path d="M12 1a9 9 0 0 0-9 9c0 7 9 13 9 13s9-6 9-13a9 9 0 0 0-9-9m0 12.5A3.5 3.5 0 1 1 15.5 10A3.5 3.5 0 0 1 12 13.5"/></svg>
          </div>
          <div>
            <div class="fw-bold mb-0">Complete Your Payment</div>
            <small class="text-muted">Step 2: Pay securely</small>
          </div>
        </div>
      </div>

      <div class="p-4 p-md-5 bg-white">
        <div class="row g-4">
          {{-- LEFT: ORDER SUMMARY --}}
          <div class="col-lg-6">
            <div class="mb-3 text-uppercase small fw-semibold text-secondary">Order Summary</div>

            <div class="d-flex align-items-start gap-3">
              <div class="rounded-3 border p-2 d-flex align-items-center justify-content-center" style="width:64px;height:64px;">
                <svg class="ck-icon-28" viewBox="0 0 24 24" fill="#344054"><path d="M21 8l-9-5l-9 5l9 5l9-5m-9 7l-9-5v6l9 5l9-5v-6l-9 5Z"/></svg>
              </div>
              <div>
                <h2 class="h5 mb-1">{{ $order->package->name ?? 'Package' }}</h2>
                <div class="small text-muted">
                  Paying for <span class="text-body fw-medium">{{ $order->name ?: 'Customer' }}</span>
                  @if($order->expires_at)
                    • Expires {{ $order->expires_at->format('d M Y, h:i A') }}
                  @endif
                </div>
                @if(!empty($order->business_name))
                  <div class="small mt-1"><span class="text-muted">For:</span> {{ $order->business_name }}</div>
                @endif
              </div>
            </div>

            <hr class="my-4">

            {{-- Amount summary (you can replace with your MRP/GST table if you like) --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-muted">Amount to pay</span>
              <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold px-3 py-2">
                ₹{{ number_format($amount/100, 2) }}
              </span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-muted">Currency</span>
              <span class="fw-medium">INR</span>
            </div>

            <div class="mt-4 p-3 rounded-3 ck-soft border">
              <div class="small text-muted mb-2">You can pay via</div>
              <ul class="list-unstyled mb-0 small">
                <li class="d-flex align-items-center gap-2"><span class="text-success">✔</span> UPI / QR</li>
                <li class="d-flex align-items-center gap-2"><span class="text-success">✔</span> Debit/Credit Cards</li>
                <li class="d-flex align-items-center gap-2"><span class="text-success">✔</span> NetBanking</li>
                <li class="d-flex align-items-center gap-2"><span class="text-success">✔</span> Wallets</li>
              </ul>
            </div>
          </div>

          {{-- RIGHT: CHECKOUT --}}
          <div class="col-lg-6">
            <div class="mb-3 text-uppercase small fw-semibold text-secondary">Checkout</div>

            <p class="text-muted">
              You’re paying <span class="fw-semibold text-body">₹{{ number_format($amount/100, 2) }}</span>
              for <span class="fw-semibold text-body">{{ $order->package->name ?? 'Package' }}</span>.
            </p>

            @if ($errors->any())
              <div class="alert alert-danger py-2 px-3">
                {{ $errors->first() }}
              </div>
            @endif

            {{-- Hidden phone (so callback has it if you need) --}}
            <input type="hidden" id="phoneHidden" value="{{ $order->phone }}">

            <button id="rzp-button" class="btn btn-success btn-lg w-100">
              Pay Now
            </button>

            <div class="text-center small text-muted mt-3">
              You’ll be redirected to secure Razorpay checkout. By continuing, you agree to our
              <a href="/terms" class="link-secondary">Terms</a> & <a href="/privacy" class="link-secondary">Privacy Policy</a>.
            </div>

            <div class="mt-4 p-3 rounded-3 bg-light border">
              <div class="d-flex align-items-center gap-2">
                <svg class="ck-icon-20 text-success" viewBox="0 0 24 24" fill="currentColor"><path d="M17 8h-1V6a4 4 0 1 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2m-6 0V6a2 2 0 1 1 4 0v2z"/></svg>
                <span class="small">Payments are encrypted & PCI-DSS compliant.</span>
              </div>
              <div class="small mt-1">Need help? <a class="link-secondary" href="/contact">Contact support</a>.</div>
            </div>
          </div>
        </div>
      </div>

    </div> {{-- /card --}}
  </div>
</section>

{{-- RAZORPAY + FORM POST (hidden phone included) --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  function postTo(url, data){
    const f = document.createElement('form'); f.method='POST'; f.action=url;
    const t = document.createElement('input'); t.type='hidden'; t.name='_token'; t.value='{{ csrf_token() }}'; f.appendChild(t);
    Object.keys(data).forEach(k=>{
      const i=document.createElement('input'); i.type='hidden'; i.name=k; i.value=data[k]; f.appendChild(i);
    });
    document.body.appendChild(f); f.submit();
  }

  const options = {
    key: "{{ $rzpKey }}",
    amount: "{{ $amount }}",  // paise
    currency: "INR",
    name: "{{ config('app.name') }}",
    description: "Order #{{ $order->id }}",
    order_id: "{{ $rzpOrder['id'] }}",
    prefill: { name: "{{ $order->name ?? '' }}", contact: "{{ $order->phone }}" },
    notes:   { payment_order_id: "{{ $order->id }}", link_token: "{{ $order->link_token }}", phone: "{{ $order->phone }}" },

    // On success: normal POST to callback (prevents stale-error redirects)
    handler: function (resp){
      postTo("{{ route('paylink.callback') }}", {
        razorpay_payment_id: resp.razorpay_payment_id,
        razorpay_order_id:   resp.razorpay_order_id,
        razorpay_signature:  resp.razorpay_signature,
        phone: document.getElementById('phoneHidden').value
      });
    },
    theme: { color: "#0d6efd" }
  };

  document.getElementById('rzp-button').addEventListener('click', function(e){
    e.preventDefault(); new Razorpay(options).open();
  });
</script>
@endsection

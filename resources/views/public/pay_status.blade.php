@extends('component.main', ['seos' => (object)[
  'meta_title' => 'Payment Successful | Thank You',
  'meta_description' => 'Your payment was successful. Thank you for your purchase!'
]])
@section('content')
<style>
  .tick-wrap{width:110px;height:110px;border-radius:50%;background:#e9f8ef;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;}
  .tick{font-size:52px;line-height:1;color:#10b981;}
  .thank-card{border-radius:18px}
  .btn-grad{background:linear-gradient(135deg,#0ea5e9,#2563eb);border:none}
  .btn-grad:hover{filter:brightness(.95)}
</style>

<!-- Header -->
<div class="container-fluid custom-color my-lg-5 py-md-4 py-sm-3 py-2">
  <div class="container text-center py-5">
    <h1 class="display-5 text-white mb-1">Thank You</h1>
    <p class="text-white-50 mb-0">Your payment has been received successfully.</p>
  </div>
</div>

@php
  // Data passed from controller (object)
  $orderId   = $data->order_id   ?? null;
  $payId     = $data->payment_id ?? null;
  $pkgName   = $data->package_name ?? 'Package';
  $amount    = $data->amount     ?? null;
  $currency  = $data->currency   ?? 'INR';
  $paidAt    = $data->paid_at    ?? null;
@endphp

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0 thank-card">
        <div class="card-body p-4 p-md-5 text-center">
          <div class="tick-wrap"><i class="bi bi-check2-circle tick"></i></div>
          <h2 class="fw-bold mb-2">Payment Successful!</h2>
          <p class="text-muted mb-4">
            Thank you for choosing us. Your order is being processed and you’ll receive onboarding details shortly on your registered contact.
          </p>

          <div class="row g-3 justify-content-center mb-4">
            @if($pkgName)
              <div class="col-md-4">
                <div class="p-3 rounded bg-light border h-100">
                  <div class="text-muted small">Package</div>
                  <div class="fw-semibold">{{ $pkgName }}</div>
                </div>
              </div>
            @endif

            @if($payId)
              <div class="col-md-4">
                <div class="p-3 rounded bg-light border h-100">
                  <div class="text-muted small">Payment ID</div>
                  <div class="fw-semibold">{{ $payId }}</div>
                </div>
              </div>
            @endif

            @if($orderId)
              <div class="col-md-4">
                <div class="p-3 rounded bg-light border h-100">
                  <div class="text-muted small">Order ID</div>
                  <div class="fw-semibold">{{ $orderId }}</div>
                </div>
              </div>
            @endif

            @if(!is_null($amount))
              <div class="col-md-4">
                <div class="p-3 rounded bg-light border h-100">
                  <div class="text-muted small">Amount</div>
                  <div class="fw-semibold">₹ {{ number_format((float)$amount, 2) }} {{ $currency }}</div>
                </div>
              </div>
            @endif

            @if($paidAt)
              <div class="col-md-4">
                <div class="p-3 rounded bg-light border h-100">
                  <div class="text-muted small">Paid At</div>
                  <div class="fw-semibold">{{ $paidAt }}</div>
                </div>
              </div>
            @endif
          </div>

          @if(!$payId && !$orderId && !$pkgName)
            <div class="alert alert-warning text-start mx-auto" style="max-width:560px">
              We couldn’t find the transaction details. If the amount is deducted, please contact support with your registered phone number.
            </div>
          @endif

          <div class="d-flex gap-2 justify-content-center">
            <a href="{{ url('/packages') }}" class="btn btn-grad text-white px-4">Browse More Packages</a>
            <a href="{{ url('/') }}" class="btn btn-secondary px-4">Go to Home</a>
            <a href="{{ url('/contact') }}" class="btn btn-success px-4">Need Help?</a>
            {{-- Call & WhatsApp (no '+' in wa.me link) --}}
            <a href="tel:+917800378002" class="btn btn-outline-dark px-4"><i class="bi bi-telephone me-1"></i> Call</a>
            <a href="https://wa.me/917800378002" target="_blank" class="btn btn-success px-4"><i class="bi bi-whatsapp me-1"></i> WhatsApp</a>
          </div>

          <p class="small text-muted mt-4 mb-0">
            Tip: Please don’t refresh this page continuously. If the amount is deducted but the status is not updated, contact support with your Payment ID.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

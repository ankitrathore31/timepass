@extends('dashboard.layouts.app')
@section('title', 'Rewards')

@section('content')
<div class="container">

  {{-- Coin header --}}
  <div style="background:linear-gradient(135deg,var(--yellow),var(--p2));border-radius:24px;padding:22px;margin-bottom:20px;display:flex;align-items:center;gap:16px;">
    <div style="font-size:3rem;">🪙</div>
    <div>
      <h3 style="font-size:1.1rem;font-weight:900;color:var(--text);">{{ $user->formatted_coins }} Coins Available</h3>
      <p style="font-size:0.8rem;color:rgba(26,26,46,.65);font-weight:700;">Earn more by playing games daily!</p>
    </div>
  </div>

  <div class="section-hd"><h3>🎁 Redeem Rewards</h3></div>
  <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:24px;">
    @foreach($rewards as $reward)
    <div style="background:white;border-radius:20px;padding:18px 14px;text-align:center;box-shadow:0 4px 14px rgba(0,0,0,.06);position:relative;overflow:hidden;transition:all .3s;"
         onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
      <div style="position:absolute;top:0;left:0;right:0;height:4px;background:{{ $reward->color_bar }};"></div>
      <div style="font-size:2.4rem;margin-bottom:10px;">{{ $reward->emoji }}</div>
      <h4 style="font-weight:900;font-size:0.88rem;margin-bottom:4px;">{{ $reward->name }}</h4>
      <p style="font-size:0.7rem;color:var(--muted);font-weight:600;line-height:1.4;margin-bottom:8px;">{{ $reward->description }}</p>
      <span style="display:inline-block;background:linear-gradient(135deg,var(--yellow),var(--p2));color:var(--text);padding:3px 12px;border-radius:20px;font-size:0.74rem;font-weight:900;margin-bottom:10px;">{{ number_format($reward->coins_required) }} pts</span>

      @if($user->coins >= $reward->coins_required && $reward->isAvailable())
      <form method="POST" action="{{ route('rewards.redeem', $reward) }}">
        @csrf
        <button type="submit" style="width:100%;background:linear-gradient(135deg,var(--p1),var(--orange));color:white;border:none;border-radius:12px;padding:9px;font-family:'Nunito',sans-serif;font-size:0.8rem;font-weight:900;cursor:pointer;"
                onclick="return confirm('Redeem {{ $reward->name }} for {{ $reward->coins_required }} coins?')">
          Redeem →
        </button>
      </form>
      @else
      <button disabled style="width:100%;background:var(--border);color:var(--muted);border:none;border-radius:12px;padding:9px;font-family:'Nunito',sans-serif;font-size:0.8rem;font-weight:900;cursor:default;">
        {{ !$reward->isAvailable() ? 'Out of Stock' : 'Not Enough Coins' }}
      </button>
      @endif
    </div>
    @endforeach
  </div>

  {{-- Transaction History --}}
  <div class="section-hd"><h3>📋 Transaction History</h3></div>
  @forelse($transactions as $txn)
  <div style="background:white;border-radius:14px;padding:12px 15px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 8px rgba(0,0,0,.05);margin-bottom:10px;">
    <div style="width:38px;height:38px;border-radius:11px;background:{{ $txn->type === 'earn' ? 'linear-gradient(135deg,#10B98122,#4ECDC422)' : 'linear-gradient(135deg,#FF6B6B22,#FF8E5322)' }};display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
      {{ $txn->type === 'earn' ? '🎮' : '🎁' }}
    </div>
    <div style="flex:1;">
      <h4 style="font-size:0.84rem;font-weight:900;margin-bottom:1px;">{{ $txn->description ?? $txn->source }}</h4>
      <p style="font-size:0.7rem;color:var(--muted);font-weight:600;">{{ $txn->created_at->diffForHumans() }}</p>
    </div>
    <div style="font-weight:900;font-size:0.86rem;color:{{ $txn->amount > 0 ? 'var(--green)' : 'var(--p1)' }};">
      {{ $txn->amount > 0 ? '+' : '' }}{{ number_format($txn->amount) }} 🪙
    </div>
  </div>
  @empty
  <p style="text-align:center;color:var(--muted);font-weight:700;padding:20px;">No transactions yet. Play a game to earn coins!</p>
  @endforelse

</div>
@endsection
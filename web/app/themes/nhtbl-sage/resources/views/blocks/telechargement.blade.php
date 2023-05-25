<div class="{{ $block->classes }}">
@if (!is_admin())<a href={{$file["url"]}} target="_blank">@endif
<div class="transition-all flex uppercase tracking-widest text-sm lg:text-base gap-2 cursor-pointer">
  <div class="self-center">
  <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
    <g clip-path="url(#clip0_115_137)">
    <mask id="mask0_115_137" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="32" height="32">
    <path d="M31.63 0H0V31.63H31.63V0Z" fill="white"/>
    </mask>
    <g mask="url(#mask0_115_137)">
    <path d="M15.86 31.42C18.9474 31.42 21.9654 30.5045 24.5324 28.7892C27.0995 27.074 29.1003 24.636 30.2817 21.7837C31.4632 18.9313 31.7724 15.7927 31.17 12.7646C30.5677 9.73659 29.081 6.95515 26.8979 4.77205C24.7149 2.58896 21.9334 1.10224 18.9053 0.499929C15.8773 -0.102387 12.7386 0.20675 9.88629 1.38823C7.03394 2.56972 4.59599 4.57051 2.88074 7.13756C1.16549 9.70461 0.25 12.7226 0.25 15.81C0.252649 19.9492 1.89808 23.9182 4.82495 26.845C7.75182 29.7719 11.7208 31.4174 15.86 31.42Z" stroke="#1D1D1B" stroke-width="0.75"/>
    </g>
    <path d="M15.9099 5.23001V26.39" stroke="#1D1D1B" stroke-width="0.75"/>
    <path d="M22.6699 18.93L15.9099 26.25" stroke="#1D1D1B" stroke-width="0.75"/>
    <path d="M8.95996 18.93L15.72 26.25" stroke="#1D1D1B" stroke-width="0.75"/>
    </g>
    <defs>
    <clipPath id="clip0_115_137">
    <rect width="31.63" height="31.63" fill="white"/>
    </clipPath>
    </defs>
    </svg>
  </div>
  <div>
  {{$label}}
  </div>
</div>
@if (!is_admin())</a>@endif

</div>

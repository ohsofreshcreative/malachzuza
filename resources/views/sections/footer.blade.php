<footer class="footer overflow-hidden relative z-10">

	<img class="absolute opacity-10 right-0 bottom-0" src="{{ get_template_directory_uri() }}/resources/images/lines-footer.svg" alt="Lines Footer" />

	<div class="__wrapper relative z-10">

		<div class="c-main">
			<div class="__widgets footer-py">

				<div class="__contact pr-20">

					@if(!empty($footer_contact['address']))
					@if(!empty($logo_footer))
					<p class="__logo">
						<img src="{{ $logo_footer['url'] }}" alt="{{ $logo_footer['alt'] ?? get_bloginfo('name') }}" class="w-full h-auto object-contain" />
					</p>
					@endif
					<div class="__txt">
						{!! $footer_contact['address'] !!}
					</div>
					@endif
					<div class="__contact-links">
						@if(!empty($footer_contact['phone']))
						<a href="tel:{{ str_replace(' ', '', $footer_contact['phone']) }}" class="__contact-link">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.79 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
							</svg>
							{{ $footer_contact['phone'] }}
						</a>
						@endif
						@if(!empty($footer_contact['email']))
						<a href="mailto:{{ $footer_contact['email'] }}" class="__contact-link">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
								<polyline points="22,6 12,13 2,6"></polyline>
							</svg>
							{{ $footer_contact['email'] }}
						</a>
						@endif
					</div>
					@if (!empty($footer_contact['social_links']))
					<div class="__social-links flex items-center gap-3">
						@foreach ($footer_contact['social_links'] as $social)
						@if (!empty($social['link']))
						<a href="{{ $social['link'] }}" target="_blank" rel="noopener" class="__social-link">
							@if (!empty($social['icon']['url']))
							<img src="{{ $social['icon']['url'] }}" alt="{{ $social['icon']['alt'] ?? '' }}" class="w-6 h-6 object-contain" />
							@endif
						</a>
						@endif
						@endforeach
					</div>
					@endif
					@if (!empty($footer_contact['hours']))
					<div class="__hours">
						{!! $footer_contact['hours'] !!}
					</div>
					@endif
				</div>

				@for ($i = 1; $i <= 4; $i++)
					@if (is_active_sidebar('sidebar-footer-' . $i))
					<div class="__widget">@php(dynamic_sidebar('sidebar-footer-' . $i))
			</div>
			@endif
			@endfor
		</div>
	</div>

	<div class="footer-bottom">
		<div class="c-main __bottom-wrapper">
			<p>Copyright ©{{ date('Y') }} {{ get_bloginfo('name') }}. All Rights Reserved</p>
			<p class="__credit">Designed &amp; Developed by
				<a target="_blank" rel="nofollow" href="https://www.ohsofresh.pl" title="OhSoFresh"><img class="oh" src="{{ get_template_directory_uri() }}/resources/images/ohsofresh.svg" alt="OhSoFresh"></a>
			</p>
		</div>
	</div>

</footer>
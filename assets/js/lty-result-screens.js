( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var overlay = document.querySelector( '.lty-rs-overlay' );
		if ( ! overlay ) {
			return;
		}

		// Lock body scroll while overlay is visible.
		document.body.classList.add( 'lty-rs-scroll-locked' );

		// Move focus into the overlay so screen readers announce it immediately.
		var firstFocusable = overlay.querySelector( 'button, [href], [tabindex]:not([tabindex="-1"])' );
		if ( firstFocusable ) {
			firstFocusable.focus();
		}

		// ── Dismiss helper ──────────────────────────────────────────────────
		function dismiss() {
			overlay.style.transition = 'opacity 0.3s ease';
			overlay.style.opacity    = '0';

			overlay.addEventListener( 'transitionend', function handler( e ) {
				if ( e.propertyName !== 'opacity' ) {
					return;
				}
				overlay.removeEventListener( 'transitionend', handler );
				overlay.style.display = 'none';
				document.body.classList.remove( 'lty-rs-scroll-locked' );

				var returnTarget = document.querySelector( 'h1, .entry-title, main' ) || document.body;
				returnTarget.focus();
			} );
		}

		// ── Delegated dismiss — survives innerHTML card swaps ────────────────
		overlay.addEventListener( 'click', function ( e ) {
			if ( e.target.closest( '[data-lty-rs-dismiss]' ) ) {
				dismiss();
			}
		} );

		// ── Esc key closes the overlay ───────────────────────────────────────
		document.addEventListener( 'keydown', function escHandler( e ) {
			if ( e.key === 'Escape' || e.key === 'Esc' ) {
				document.removeEventListener( 'keydown', escHandler );
				dismiss();
			}
		} );

		// ── Copy coupon code to clipboard (delegated) ───────────────────────
		function copyText( text ) {
			if ( navigator.clipboard && navigator.clipboard.writeText ) {
				return navigator.clipboard.writeText( text );
			}
			var ta = document.createElement( 'textarea' );
			ta.value = text;
			ta.style.cssText = 'position:fixed;opacity:0;pointer-events:none';
			document.body.appendChild( ta );
			ta.select();
			document.execCommand( 'copy' );
			document.body.removeChild( ta );
			return Promise.resolve();
		}

		overlay.addEventListener( 'click', function ( e ) {
			var btn = e.target.closest( '.lty-rs-copy-code' );
			if ( ! btn ) {
				return;
			}
			var code    = btn.dataset.code;
			var confirm = btn.querySelector( '.lty-rs-copy-code__confirm' );

			copyText( code ).then( function () {
				if ( confirm ) {
					confirm.classList.remove( 'ltyrs-hidden' );
					setTimeout( function () {
						confirm.classList.add( 'ltyrs-hidden' );
					}, 2000 );
				}
			} );
		} );

		// ── Focus trap — refreshable after card swap ─────────────────────────
		function bindFocusTrap() {
			var focusables = Array.prototype.slice.call(
				overlay.querySelectorAll( 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])' )
			).filter( function ( el ) {
				return ! el.disabled && el.offsetParent !== null;
			} );

			if ( ! focusables.length ) {
				return;
			}

			var first = focusables[ 0 ];
			var last  = focusables[ focusables.length - 1 ];

			// Remove any previous trap listener before rebinding.
			overlay.removeEventListener( 'keydown', overlay._ltyTrapHandler );
			overlay._ltyTrapHandler = function ( e ) {
				if ( e.key !== 'Tab' ) {
					return;
				}
				if ( e.shiftKey ) {
					if ( document.activeElement === first ) {
						e.preventDefault();
						last.focus();
					}
				} else {
					if ( document.activeElement === last ) {
						e.preventDefault();
						first.focus();
					}
				}
			};
			overlay.addEventListener( 'keydown', overlay._ltyTrapHandler );
		}

		bindFocusTrap();

		// ── Swap card content after polling resolves ─────────────────────────
		function swapCard( html ) {
			// The server renders the full overlay + card. Extract just the inner
			// card div (first child of the overlay) to avoid double-scrim.
			var tmp = document.createElement( 'div' );
			tmp.innerHTML = html;
			var newOverlay = tmp.querySelector( '.lty-rs-overlay' );
			var newCard    = newOverlay ? newOverlay.querySelector( '.ltyrs-relative' ) : null;

			if ( ! newCard ) {
				return;
			}

			// Find the existing card and replace it, preserving the outer overlay.
			var oldCard = overlay.querySelector( '.ltyrs-relative' );
			if ( oldCard ) {
				overlay.replaceChild( newCard, oldCard );
			} else {
				overlay.appendChild( newCard );
			}

			// Remove the pending attribute so we stop polling on next tick.
			overlay.removeAttribute( 'data-lty-rs-pending' );

			// Re-apply entrance animation.
			newCard.classList.remove( 'ltyrs-animate-rs-enter' );
			// Trigger reflow so the animation restarts.
			void newCard.offsetWidth; // eslint-disable-line no-void
			newCard.classList.add( 'ltyrs-animate-rs-enter' );

			// Move focus into new card.
			var focusTarget = newCard.querySelector( 'button, [href], [tabindex]:not([tabindex="-1"])' );
			if ( focusTarget ) {
				focusTarget.focus();
			}

			// Rebind focus trap.
			bindFocusTrap();
		}

		// ── Show neutral fallback (timeout / unresolved) ─────────────────────
		function showFallback( message ) {
			var card = overlay.querySelector( '.ltyrs-relative' );
			if ( ! card ) {
				return;
			}

			// Build a minimal, dismissible fallback card reusing existing classes.
			var browse_url = ( window.ltyResultScreens && window.ltyResultScreens.browseUrl )
				? window.ltyResultScreens.browseUrl
				: '';

			var inner = '<button class="lty-rs-close-x" data-lty-rs-dismiss aria-label="Close">&#10005;</button>'
				+ '<div aria-live="polite" aria-atomic="true">'
				+ '<p class="ltyrs-text-5xl ltyrs-mb-2" aria-hidden="true">&#9989;</p>'
				+ '<p class="ltyrs-text-[clamp(1.5rem,4vw,2rem)] ltyrs-font-extrabold ltyrs-tracking-tight ltyrs-text-[var(--color-ink,#1e2a1e)] ltyrs-mb-2 ltyrs-leading-tight">'
				+ escapeHtml( message )
				+ '</p>'
				+ '</div>'
				+ ( browse_url
					? '<a href="' + escapeHtml( browse_url ) + '" class="lty-rs-btn lty-rs-btn-no-win" data-lty-rs-dismiss>Browse competitions</a>'
					: '<button class="lty-rs-btn lty-rs-btn-no-win" data-lty-rs-dismiss>Close</button>' );

			card.innerHTML = inner;
			overlay.removeAttribute( 'data-lty-rs-pending' );

			var focusTarget = card.querySelector( 'button, [href]' );
			if ( focusTarget ) {
				focusTarget.focus();
			}

			bindFocusTrap();
		}

		function escapeHtml( str ) {
			var div = document.createElement( 'div' );
			div.appendChild( document.createTextNode( str ) );
			return div.innerHTML;
		}

		// ── Polling (only when pending screen is shown) ───────────────────────
		if ( ! overlay.hasAttribute( 'data-lty-rs-pending' ) ) {
			return;
		}

		var cfg = window.ltyResultScreens;
		if ( ! cfg || ! cfg.restUrl || ! cfg.orderKey ) {
			return;
		}

		// Fibonacci-ish backoff sequence capped at pollMs.
		var backoffSequence = [ 1000, 1000, 2000, 3000, 5000 ];
		var backoffIndex    = 0;
		var elapsed         = 0;
		var timeoutMs       = cfg.timeoutMs || 90000;
		var pollMs          = cfg.pollMs    || 2000;
		var fallback        = cfg.fallbackMessage || 'Your entry is confirmed — we’ll email your result shortly.';
		var pollTimer       = null;
		var stopped         = false;

		function getNextDelay() {
			var delay = backoffSequence[ backoffIndex ] || pollMs;
			if ( backoffIndex < backoffSequence.length - 1 ) {
				backoffIndex++;
			}
			return Math.min( delay, pollMs );
		}

		function stopPolling() {
			stopped = true;
			if ( pollTimer ) {
				clearTimeout( pollTimer );
			}
		}

		function schedulePoll( delay ) {
			pollTimer = setTimeout( poll, delay );
		}

		function poll() {
			if ( stopped ) {
				return;
			}

			var url = cfg.restUrl + '?key=' + encodeURIComponent( cfg.orderKey );

			fetch( url, { credentials: 'omit' } )
				.then( function ( res ) {
					if ( res.status === 429 ) {
						// Back off aggressively on rate-limit.
						backoffIndex = backoffSequence.length - 1;
						var delay = pollMs * 2;
						elapsed += delay;
						if ( elapsed >= timeoutMs ) {
							stopPolling();
							showFallback( fallback );
							return;
						}
						schedulePoll( delay );
						return;
					}

					if ( ! res.ok ) {
						// Non-429 error — show fallback.
						stopPolling();
						showFallback( fallback );
						return;
					}

					return res.json().then( function ( data ) {
						if ( stopped ) {
							return;
						}

						if ( data.state === 'won' || data.state === 'no_win' || data.state === 'draw' ) {
							stopPolling();
							if ( data.html ) {
								swapCard( data.html );
							}
							return;
						}

						if ( data.state === 'unresolved' ) {
							stopPolling();
							showFallback( fallback );
							return;
						}

						// Still pending — schedule next poll.
						var delay = getNextDelay();
						elapsed += delay;
						if ( elapsed >= timeoutMs ) {
							stopPolling();
							showFallback( fallback );
							return;
						}
						schedulePoll( delay );
					} );
				} )
				.catch( function () {
					if ( stopped ) {
						return;
					}
					// Network error — show fallback.
					stopPolling();
					showFallback( fallback );
				} );
		}

		// Begin polling after initial delay.
		schedulePoll( getNextDelay() );
	} );
} )();

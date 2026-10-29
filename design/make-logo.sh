#!/bin/sh
# Regenerate every logo asset of splotnikov-dev from the prompt mark (chevron and underscore in a rounded square) in a given colour.
# usage: make-logo.sh <accent hex> <accent light hex> [outdir]   (danger: low, writes only into outdir)
set -eu
A="$1"; B="$2"; OUT="${3:-$HOME/sd/splotnikov-dev/public}"
mkdir -p "$OUT"
logo() { # $1 viewBox, $2 stroke width, $3 opacity of the square
  printf '<svg xmlns="http://www.w3.org/2000/svg" viewBox="%s"><defs><linearGradient id="g1" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="%s"/><stop offset="1" stop-color="%s"/></linearGradient></defs><rect x="4" y="4" width="40" height="40" rx="10" fill="none" stroke="%s" stroke-width="%s" opacity="%s"/><path d="M15 17l8 7-8 7" fill="none" stroke="%s" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round"/><path d="M26 31h8" fill="none" stroke="url(#g1)" stroke-width="%s" stroke-linecap="round"/></svg>' \
    "$1" "$A" "$B" "$A" "$2" "$3" "$A" "$2" "$2"
}
logo "0 0 48 48" 4 "0.35" > "$OUT/logo.svg"
logo "0 0 48 48" 5 "0.45" > "$OUT/icon.svg"
rsvg-convert -w 16 -h 16 "$OUT/icon.svg" -o "$OUT/favicon-16x16.png"
rsvg-convert -w 32 -h 32 "$OUT/icon.svg" -o "$OUT/favicon-32x32.png"
rsvg-convert -w 192 -h 192 "$OUT/icon.svg" -o "$OUT/android-chrome-192x192.png"
rsvg-convert -w 512 -h 512 "$OUT/icon.svg" -o "$OUT/android-chrome-512x512.png"
# touch icon: light background behind the mark
T="$(mktemp --suffix=.png)"
rsvg-convert -w 140 -h 140 "$OUT/icon.svg" -o "$T"
magick -size 180x180 xc:'#f5f5f5' "$T" -gravity center -composite "$OUT/apple-touch-icon.png"
rm -f "$T"
echo "logo assets written to $OUT in $A -> $B"

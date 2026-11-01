#!/usr/bin/env python3
"""Live smoke check: python3 scripts/live-smoke.py https://splotnikov.dev [scripts/smoke-urls.txt]

For every line of the list (status | path | marker): GET with a 15 s timeout, three tries 20 s apart, and require the
expected status, the marker in the body and an answer under 3 s. One line per address; exit 1 and a list of every
failing address when any fails. Standard library only."""
import sys
import time
import urllib.error
import urllib.request

base = (sys.argv[1] if len(sys.argv) > 1 else 'https://splotnikov.dev').rstrip('/')
listfile = sys.argv[2] if len(sys.argv) > 2 else 'scripts/smoke-urls.txt'
TRIES, WAIT, TIMEOUT, SLOW = 3, int(__import__('os').environ.get('SMOKE_WAIT', '20')), 15, 3.0


def fetch(url):
    req = urllib.request.Request(url, headers={'User-Agent': 'splotnikov-live-smoke/1'})
    start = time.time()
    try:
        with urllib.request.urlopen(req, timeout=TIMEOUT) as r:
            status, body = r.status, r.read().decode('utf-8', 'replace')
    except urllib.error.HTTPError as e:
        status, body = e.code, e.read().decode('utf-8', 'replace')
    return status, body, time.time() - start


def check(want, path, marker):
    problem = 'no answer'
    for attempt in range(TRIES):
        try:
            status, body, took = fetch(base + path)
            problems = []
            if status != want:
                problems.append(f'status {status}, expected {want}')
            if marker not in body:
                problems.append(f'marker not found: {marker}')
            if took > SLOW:
                problems.append(f'slow: {took:.1f} s')
            if not problems:
                return True, f'{took:.2f} s'
            problem = '; '.join(problems)
        except Exception as e:  # timeout, DNS, TLS
            problem = f'{type(e).__name__}: {e}'
        if attempt < TRIES - 1:
            time.sleep(WAIT)
    return False, problem


failed = []
for line in open(listfile, encoding='utf-8'):
    line = line.rstrip('\n')
    if not line.strip() or line.lstrip().startswith('#'):
        continue
    want, path, marker = [p.strip() for p in line.split('|', 2)]
    ok, info = check(int(want), path, marker)
    print(('OK   ' if ok else 'FAIL ') + f'{path}  {info}')
    if not ok:
        failed.append(f'{path}: {info}')

if failed:
    print(f'\n{len(failed)} address(es) failed:')
    for f in failed:
        print('  ' + f)
    sys.exit(1)
print('\nAll addresses fine.')

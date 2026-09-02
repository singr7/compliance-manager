// Minimal structured (JSON-line) logger. Never pass request bodies, passwords, tokens,
// or uploaded evidence content here — only method/path/status/duration and error messages.
function write(level, message, fields = {}) {
  const line = { level, message, time: new Date().toISOString(), ...fields };
  const out = level === 'error' ? process.stderr : process.stdout;
  out.write(JSON.stringify(line) + '\n');
}

export const logger = {
  info: (message, fields) => write('info', message, fields),
  error: (message, fields) => write('error', message, fields),
};

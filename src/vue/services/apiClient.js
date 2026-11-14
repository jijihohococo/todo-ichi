function buildHeaders() {
  const headers = {
    "Content-Type": "application/json",
    Accept: "application/json",
  };

  return headers;
}

async function readJsonResponse(response) {
  const text = await response.text();

  if (!text) {
    return null;
  }

  try {
    return JSON.parse(text);
  } catch (error) {
    console.error("Invalid JSON response:", text);
    return null;
  }
}

// Shared request handler
async function apiRequest(url, options) {
  let response = await fetch(url, {
    ...options,
    headers: buildHeaders(),
  });

  const data = await readJsonResponse(response);

  return {
    ok: response.ok,
    status: response.status,
    data,
  };
}

export async function apiGet(url) {
  return apiRequest(url, {
    method: "GET",
  });
}

export async function apiPost(url, body) {
  return apiRequest(url, {
    method: "POST",
    body: JSON.stringify(body),
  });
}

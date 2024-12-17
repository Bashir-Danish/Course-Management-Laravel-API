// fetch.js - Custom Fetch Module
export async function customFetch(url, options = {}) {
    const token = localStorage.getItem('token'); 
  
    const headers = {
      'Content-Type': 'application/json',
      ...options.headers,
    };
    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }
  
    const updatedOptions = {
      ...options,
      headers,
    };
  
    try {
      const response = await fetch(url, updatedOptions);
  
      // Save new token if provided
      if (response.ok && response.headers.has('Authorization')) {
        const newToken = response.headers.get('Authorization').replace('Bearer ', '');
        localStorage.setItem('token', newToken);
      }
  
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
  
      return await response.json();
    } catch (error) {
      console.error('Fetch failed:', error);
      throw error;
    }
  }
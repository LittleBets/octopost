const templates = [{ id: 'amazon-product-listing', title: 'Amazon Product Listing' }]

const tones = [
  { id: 'friendly', title: 'Friendly' },
  { id: 'professional', title: 'Professional' },
  { id: 'formal', title: 'Formal' },
  { id: 'witty', title: 'Witty' },
  { id: 'funny', title: 'Funny' },
  { id: 'informal', title: 'Informal' },
  { id: 'casual', title: 'Casual' },
  { id: 'silly', title: 'Silly' },
  { id: 'serious', title: 'Serious' },
  { id: 'polite', title: 'Polite' },
  { id: 'rude', title: 'Rude' },
  { id: 'sarcastic', title: 'Sarcastic' },
  { id: 'insulting', title: 'Insulting' },
  { id: 'inspiring', title: 'Inspiring' },
  { id: 'motivational', title: 'Motivational' },
  { id: 'encouraging', title: 'Encouraging' },
  { id: 'disappointing', title: 'Disappointing' },
  { id: 'disheartening', title: 'Disheartening' },
  { id: 'disgusting', title: 'Disgusting' },
]

const audiences = [
  { id: 'parents', title: 'Parents' },
  { id: 'women', title: 'Women' },
  { id: 'men', title: 'Men' },
  { id: 'babies', title: 'Babies' },
  { id: 'toddlers', title: 'Toddlers' },
  { id: 'kids', title: 'Kids' },
  { id: 'teenagers', title: 'Teenagers' },
  { id: 'adults', title: 'Adults' },
  { id: 'seniors', title: 'Seniors' },
  { id: 'adventurers', title: 'Adventurers' },
  { id: 'beauty_fans', title: 'Beauty Fans' },
  { id: 'trendsetters', title: 'trendsetters' },
]

const compositionLengths = [
  { key: 'short', name: 'Short', description: 'About 1-2 sentences. Cost: ~100 credits' },
  { key: 'medium', name: 'Medium', description: 'About 2-4 sentences. Cost: ~160 credits' },
  { key: 'long', name: 'Long', description: 'About 3-5 sentences. Cost: ~240 credits' },
]

export { templates, tones, audiences, compositionLengths }

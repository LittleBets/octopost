import { CompositionTemplateType } from '@/enums'

const templates: TemplateType[] = [
  {
    id: CompositionTemplateType.AmazonProductListing,
    title: 'Amazon Product Listing',
  },
  {
    id: CompositionTemplateType.Freeform,
    title: 'Freeform',
  },
  {
    id: CompositionTemplateType.Response,
    title: 'Response',
  },
]

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
  { id: 'kids', title: 'Kids' },
  { id: 'teenagers', title: 'Teenagers' },
  { id: 'adults', title: 'Adults' },
  { id: 'clients', title: 'Clients' },
  { id: 'friends', title: 'Friends' },
  { id: 'boss', title: 'Boss' },
  { id: 'seniors', title: 'Seniors' },
  { id: 'adventurers', title: 'Adventurers' },
  { id: 'beauty_fans', title: 'Beauty Fans' },
  { id: 'trendsetters', title: 'trendsetters' },
]

const compositionLengths = [
  { id: 'short', title: 'Short', description: 'About 1-2 sentences. Cost: ~100 credits' },
  { id: 'medium', title: 'Medium', description: 'About 2-4 sentences. Cost: ~160 credits' },
  { id: 'long', title: 'Long', description: 'About 3-5 sentences. Cost: ~240 credits' },
]

const responseTypes = [
  { id: 'email', title: 'Email' },
  { id: 'tweet', title: 'Tweet' },
  { id: 'fb', title: 'Facebook Post' },
  { id: 'message', title: 'Message' },
]

export { templates, tones, audiences, compositionLengths, responseTypes }
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
  {
    id: CompositionTemplateType.Rewrite,
    title: 'Rewrite • Cleanup • Expand • Compact',
  },
  {
    id: CompositionTemplateType.Summary,
    title: 'Summary',
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
  { id: 'clients', title: 'Clients' },
  { id: 'boss', title: 'Boss' },
  { id: 'friends', title: 'Friends' },
  { id: 'parents', title: 'Parents' },
  { id: 'women', title: 'Women' },
  { id: 'men', title: 'Men' },
  { id: 'kids', title: 'Kids' },
  { id: 'teenagers', title: 'Teenagers' },
  { id: 'adults', title: 'Adults' },
  { id: 'seniors', title: 'Seniors' },
  { id: 'adventurers', title: 'Adventurers' },
  { id: 'trendsetters', title: 'trendsetters' },
]

const summaryOutputFormats = [
  { id: 'paragraph', title: 'Paragraph' },
  { id: 'bullet points', title: 'Bullet Points' },
]

const rewriteTypes = [
  { id: 'rewrite', title: 'Rewrite' },
  { id: 'expand', title: 'Expand Text' },
  { id: 'condense', title: 'Compact Text' },
  { id: 'correct', title: 'Correct Grammar' },
]

const compositionLengths = [
  // 100 words = 75 tokens
  {
    id: 'short',
    title: 'Short',
    description: 'About 1-2 sentences. Cost: ~130 word credits',
  },
  {
    id: 'medium',
    title: 'Medium',
    description: 'About 2-4 sentences. Cost: ~180 word credits',
  },
  {
    id: 'long',
    title: 'Long',
    description: 'About 3-5 sentences. Cost: ~240 word credits',
  },
]

const responseTypes = [
  { id: 'email', title: 'Email' },
  { id: 'tweet', title: 'Tweet' },
  { id: 'facebook post', title: 'Facebook Post' },
  { id: 'message', title: 'Message' },
]

export {
  templates,
  tones,
  audiences,
  compositionLengths,
  responseTypes,
  summaryOutputFormats,
  rewriteTypes,
}

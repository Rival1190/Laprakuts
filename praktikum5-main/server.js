const express = require('express');
const { Configuration, OpenAIApi } = require('openai');
require('dotenv').config();

const app = express();
app.use(express.json());

const openai = new OpenAIApi(new Configuration({ apiKey: process.env.OPENAI_API_KEY }));

app.post('/api/chat', async (req, res) => {
  const { message } = req.body;
  const response = await openai.createChatCompletion({
    model: 'gpt-3.5-turbo',
    messages: [{ role: 'user', content: message }]
  });
  res.json({ reply: response.data.choices[0].message.content });
});

app.listen(3000, () => console.log('Server di :3000'));


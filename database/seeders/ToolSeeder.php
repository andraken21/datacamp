<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tool;
use Illuminate\Support\Str;

class ToolSeeder extends Seeder {
    public function run(): void {
        $tools = [
            ['name'=>'LangChain','category'=>'Framework','language'=>'Python','difficulty'=>'Menengah','rating'=>4.9,'icon_text'=>'LC','icon_color'=>'#1a1060','tags'=>['chains','agents','memory'],'description'=>'Framework paling populer untuk membangun aplikasi LLM dengan chains, agents, dan memory terintegrasi.','source_url'=>'https://github.com/langchain-ai/langchain','stars_github'=>95000,'is_featured'=>true],
            ['name'=>'CrewAI','category'=>'Multi-Agent','language'=>'Python','difficulty'=>'Menengah','rating'=>4.8,'icon_text'=>'CR','icon_color'=>'#0d2b20','tags'=>['multi-agent','roles','workflow'],'description'=>'Orkestrasi multi-agent berbasis peran untuk workflow AI yang kompleks dan terstruktur.','source_url'=>'https://github.com/crewAIInc/crewAI','stars_github'=>22000,'is_featured'=>true],
            ['name'=>'AutoGen','category'=>'Multi-Agent','language'=>'Python','difficulty'=>'Menengah','rating'=>4.7,'icon_text'=>'AG','icon_color'=>'#1a1a40','tags'=>['microsoft','conversational','multi-agent'],'description'=>'Framework Microsoft untuk membangun sistem multi-agent yang conversational dan adaptif.','source_url'=>'https://github.com/microsoft/autogen','stars_github'=>34000,'is_featured'=>true],
            ['name'=>'LlamaIndex','category'=>'Memory','language'=>'Python','difficulty'=>'Menengah','rating'=>4.8,'icon_text'=>'LI','icon_color'=>'#2a1a00','tags'=>['RAG','indexing','data'],'description'=>'Framework untuk menghubungkan LLM dengan data eksternal melalui RAG dan indexing canggih.','source_url'=>'https://github.com/run-llama/llama_index','stars_github'=>38000,'is_featured'=>true],
            ['name'=>'LangGraph','category'=>'Planning','language'=>'Python','difficulty'=>'Expert','rating'=>4.7,'icon_text'=>'LG','icon_color'=>'#1a1060','tags'=>['graph','stateful','flow'],'description'=>'Extension LangChain untuk membangun agent stateful dengan graph-based flow control.','source_url'=>'https://github.com/langchain-ai/langgraph','stars_github'=>9000,'is_featured'=>false],
            ['name'=>'Haystack','category'=>'Memory','language'=>'Python','difficulty'=>'Menengah','rating'=>4.6,'icon_text'=>'HY','icon_color'=>'#0d2020','tags'=>['NLP','RAG','pipeline'],'description'=>'Framework end-to-end untuk membangun NLP dan RAG pipelines yang production-ready.','source_url'=>'https://github.com/deepset-ai/haystack','stars_github'=>17000,'is_featured'=>false],
            ['name'=>'Semantic Kernel','category'=>'Framework','language'=>'TypeScript','difficulty'=>'Menengah','rating'=>4.6,'icon_text'=>'SK','icon_color'=>'#102030','tags'=>['microsoft','SDK','plugins'],'description'=>'SDK Microsoft untuk mengintegrasikan AI ke aplikasi dengan plugin dan memory management.','source_url'=>'https://github.com/microsoft/semantic-kernel','stars_github'=>23000,'is_featured'=>false],
            ['name'=>'LangSmith','category'=>'Monitoring','language'=>'Python','difficulty'=>'Pemula','rating'=>4.7,'icon_text'=>'LS','icon_color'=>'#2a1010','tags'=>['monitoring','debug','eval'],'description'=>'Platform monitoring, debugging, dan evaluasi untuk aplikasi LLM berbasis LangChain.','source_url'=>'https://smith.langchain.com','stars_github'=>0,'is_featured'=>false],
            ['name'=>'AutoGPT','category'=>'Planning','language'=>'Python','difficulty'=>'Pemula','rating'=>4.5,'icon_text'=>'AT','icon_color'=>'#1a2c10','tags'=>['autonomous','planning','task'],'description'=>'Agent otonom yang mampu merencanakan dan mengeksekusi task secara mandiri.','source_url'=>'https://github.com/Significant-Gravitas/AutoGPT','stars_github'=>168000,'is_featured'=>false],
            ['name'=>'Flowise','category'=>'Tool Use','language'=>'JavaScript','difficulty'=>'Pemula','rating'=>4.6,'icon_text'=>'FL','icon_color'=>'#0a2020','tags'=>['no-code','visual','flow'],'description'=>'Low-code tool untuk membangun LLM apps dan DataCamp secara visual dengan drag and drop.','source_url'=>'https://github.com/FlowiseAI/Flowise','stars_github'=>32000,'is_featured'=>false],
        ];

        foreach ($tools as $tool) {
            Tool::create(array_merge($tool, ['slug' => Str::slug($tool['name'])]));
        }
    }
}
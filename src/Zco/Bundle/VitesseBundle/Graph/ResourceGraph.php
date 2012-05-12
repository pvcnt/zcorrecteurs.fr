<?php

/**
 * Copyright 2012 Corrigraphie
 * 
 * This file is part of zCorrecteurs.fr.
 *
 * zCorrecteurs.fr is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * zCorrecteurs.fr is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with zCorrecteurs.fr. If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
 
namespace Zco\Bundle\VitesseBundle\Graph;

final class ResourceGraph extends AbstractDirectedGraph
{
    private $resourceGraph = array();
    private $graphSet = false;

    protected function loadEdges(array $nodes)
    {
        if (!$this->graphSet)
        {
            throw new \RuntimeException('Call setResourceGraph before loading the graph!');
        }

        $graph = $this->getResourceGraph();
        $edges = array();
        
        foreach ($nodes as $node)
        {
            $edges[$node] = isset($graph[$node]) ? $graph[$node] : array();
        }
        
        return $edges;
    }

    final public function setResourceGraph(array $graph)
    {
        $this->resourceGraph = $graph;
        $this->graphSet = true;
    }

    private function getResourceGraph()
    {
        return $this->resourceGraph;
    }
}
